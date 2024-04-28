<?php

namespace Tests\Feature\Controllers\Contacts;

use App\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindContactControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldReceiveIdentifierAndReturnContact(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(sprintf('/contacts/%s', $contact->id), [
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
}