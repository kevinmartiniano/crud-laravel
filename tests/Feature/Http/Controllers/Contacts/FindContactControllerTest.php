<?php

namespace Tests\Feature\Controllers\Contacts;

use App\Models\Contact;
use App\Services\ContactService;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindContactControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function testShouldReceiveIdentifierAndReturnContact(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(sprintf('/contacts/%s', $contact->id), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name',
                'company',
                'phone_number',
                'mobile_number',
                'email',
                'birth_date',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
        
        $responseContact = data_get(json_decode($response->content(), true), 'data');
        $this->assertEquals($contact->toArray(), $responseContact);
    }

    public function testShouldReceiveIdentifierAndReturnNotFound(): void
    {
        $response = $this->get(sprintf('/contacts/%s', $this->faker->randomNumber(1)), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShouldReceiveIdentifierAndReturnException(): void
    {
        $id = $this->faker->randomNumber(1);
        
        $service = Mockery::mock(ContactService::class)->makePartial();
        $this->app->instance(ContactService::class, $service);

        $exception = new Exception();
        $service->shouldReceive('findContact')
            ->with($id)
            ->andThrows($exception)
            ->once();
        
        Log::shouldReceive('error')
            ->with(
                'error_find_contact_controller',
                [
                    'message' => $exception->getMessage(),
                    'stack_trace' => $exception->getTraceAsString(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            )
            ->once();

        $response = $this->get(sprintf('/contacts/%s', $id), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testShouldReceiveIdentifierAndReturnUnprocessableEntity(): void
    {
        $response = $this->get(sprintf('/contacts/%s', $this->faker->word()), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}