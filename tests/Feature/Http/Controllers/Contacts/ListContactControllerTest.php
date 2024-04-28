<?php

namespace Tests\Feature\Controllers\Contacts;

use App\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ListContactControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldRequestGetAndReturnListContact(): void
    {
        Contact::factory(2)->create();

        $response = $this->get('/contacts', [
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
}