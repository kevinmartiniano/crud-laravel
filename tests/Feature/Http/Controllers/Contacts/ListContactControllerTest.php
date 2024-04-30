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

class ListContactControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function testShouldRequestGetAndReturnListContact(): void
    {
        Contact::factory(2)->create();

        $response = $this->get('/contacts', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $contactFields = [
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
        ];

        $response->assertJsonStructure([
            'data' => [
                $contactFields,
                $contactFields,
            ],
        ]);
    }

    public function testShouldRequestGetAndReturnFilteredListContact(): void
    {
        $contacts = Contact::factory(2)->create();

        $response = $this->get(sprintf('/contacts?first_name=%s', $contacts->first()->first_name), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'data' => [
                $contacts->first()->toArray(),
            ],
        ]);
    }

    public function testShouldRequestGetAndReturnUnprocessableEntity(): void
    {
        $response = $this->get(sprintf('/contacts?id=%s', $this->faker->word()), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldRequestGetAndReturnException(): void
    {
        $service = Mockery::mock(ContactService::class)->makePartial();
        $this->app->instance(ContactService::class, $service);

        $exception = new Exception();
        $service->shouldReceive('findByArgs')
            ->andThrows($exception)
            ->once();

        Log::shouldReceive('error')
            ->with(
                'error_list_contact_controller',
                [
                    'message' => $exception->getMessage(),
                    'stack_trace' => $exception->getTraceAsString(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            )
            ->once();

        $response = $this->get('/contacts', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
