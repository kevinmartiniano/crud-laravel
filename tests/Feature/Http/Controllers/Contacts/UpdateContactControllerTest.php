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

class UpdateContactControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function testShouldRequestUpdateAndReturnSuccess(): void
    {
        $contact = Contact::factory()->create([
            'id' => $this->faker->randomNumber(),
        ]);

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $update = [
            'mobile_number' => $this->faker->phoneNumber(),
        ];

        $response = $this->putJson(
            sprintf('/contacts/%s', $contact->id),
            $update,
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
            'deleted_at',
        ];

        $response->assertJsonStructure([
            'data' => $contactFields,
        ]);

        $this->assertDatabaseHas('contacts', $update);
    }

    public function testShouldRequestUpdateContactAndReturnErrorStatus(): void
    {
        $contact = Contact::factory()->make([
            'id' => $this->faker->randomNumber(),
        ]);

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $update = [
            'mobile_number' => $this->faker->phoneNumber(),
        ];
        
        $exception = new Exception();

        $service = Mockery::mock(ContactService::class)->makePartial();
        $service->shouldReceive('updateContact')
            ->with($contact->id, $update)
            ->andThrows($exception)
            ->once();
        
        $this->app->instance(ContactService::class, $service);

        Log::shouldReceive('error')
            ->with(
                'error_update_contact_controller',
                [
                    'message' => $exception->getMessage(),
                    'stack_trace' => $exception->getTraceAsString(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            )
            ->once();

        $response = $this->putJson(
            sprintf('/contacts/%s', $contact->id),
            $update,
            $headers
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertDatabaseMissing('contacts', $update);
    }

    public function testShouldRequestCreateEmptyRequiredFieldAndReceiveUnprocessableEntity(): void
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $update = [
            'birth_date' => $this->faker->word(),
        ];

        $response = $this->putJson(
            sprintf('/contacts/%s', $this->faker->randomNumber(2, true)),
            $update,
            $headers
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldRequestUpdateAndNotExecuteSaveFunction(): void
    {
        $updatedAt = $this->faker->date();

        $contact = Contact::factory()->create([
            'id' => $this->faker->randomNumber(),
            'updated_at' => $updatedAt,
        ]);

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $update = [
            'mobile_number' => $contact->mobile_number,
        ];

        $response = $this->putJson(
            sprintf('/contacts/%s', $contact->id),
            $update,
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
            'deleted_at',
        ];

        $response->assertJsonStructure([
            'data' => $contactFields,
        ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'updated_at' => $updatedAt,
        ]);
    }
}
