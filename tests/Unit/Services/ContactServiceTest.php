<?php

namespace Tests\Unit\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use WithFaker;

    private MockInterface $repository;
    private ContactService $service;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(ContactRepository::class);
        $this->service = new ContactService($this->repository);
    }

    #[DataProvider('simpleFindResult')]
    public function testShouldSearchContactById(?Contact $contact): void
    {
        $id = $this->faker->randomDigitNotNull();

        $this->repository
            ->shouldReceive('find')
            ->with($id)
            ->andReturn($contact)
            ->once();

        $response = $this->service->findContact($id);

        match ($contact) {
            null => $this->assertNotInstanceOf(Contact::class, $contact),
            default => $this->assertInstanceOf(Contact::class, $response),
        };

        $expectedEquals = $contact instanceof Contact ? $contact->toArray() : null;
        $responseEquals = $contact instanceof Contact ? $response->toArray() : null;

        $this->assertEquals($expectedEquals, $responseEquals);
    }

    public static function simpleFindResult(): array
    {
        return [
            'find contact by id and return contact record' => [
                Contact::factory()->make(),
            ],
            'find contact by id and return null' => [
                null,
            ],
        ];
    }
}