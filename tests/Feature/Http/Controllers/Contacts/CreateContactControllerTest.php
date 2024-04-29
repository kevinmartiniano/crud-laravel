<?php

namespace Tests\Feature\Controllers\Contacts;

use App\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
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

    public function testShouldRequestCreateEmptyRequiredFieldAndReturnErrorStatus(): void
    {
        $contact = Contact::factory()->make();
        $contact->first_name = null;

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        Log::shouldReceive('error')
            ->withArgs(function ($message, $context) {
                return 
                    $message === 'error_create_contact_controller' &&
                    array_key_exists('message', $context) &&
                    array_key_exists('stack_trace', $context) &&
                    array_key_exists('file', $context) &&
                    array_key_exists('line', $context);
            })
            ->once();

        $response = $this->postJson(
            '/contacts',
            $contact->toArray(),
            $headers
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }
}
