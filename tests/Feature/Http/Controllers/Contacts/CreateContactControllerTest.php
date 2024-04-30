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

class CreateContactControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function testShouldRequestCreateAndReturnCreatedStatus(): void
    {
        $contact = Contact::factory()->make();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $response = $this->postJson(
            '/contacts',
            $contact->toArray(),
            $headers
        );

        $response->assertStatus(Response::HTTP_CREATED);

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
        ];

        $response->assertJsonStructure([
            'data' => $contactFields,
        ]);

        $this->assertDatabaseHas('contacts', $contact->toArray());
    }

    public function testShouldRequestCreateContactAndReturnErrorStatus(): void
    {
        $contact = Contact::factory()->make();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $exception = new Exception();

        $service = Mockery::mock(ContactService::class)->makePartial();
        $service->shouldReceive('createContact')
            ->with($contact->toArray())
            ->andThrows($exception)
            ->once();

        $this->app->instance(ContactService::class, $service);

        Log::shouldReceive('error')
            ->with(
                'error_create_contact_controller',
                [
                    'message' => $exception->getMessage(),
                    'stack_trace' => $exception->getTraceAsString(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            )
            ->once();

        $response = $this->postJson(
            '/contacts',
            $contact->toArray(),
            $headers
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    public function testShouldRequestCreateEmptyRequiredFieldAndReceiveUnprocessableEntity(): void
    {
        $contact = Contact::factory()->make();
        $contact->first_name = null;

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $response = $this->postJson(
            '/contacts',
            $contact->toArray(),
            $headers
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
