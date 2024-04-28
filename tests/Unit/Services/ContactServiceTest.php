<?php

namespace Tests\Unit\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Services\ContactService;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
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

    #[DataProvider('findListResult')]
    public function testShouldSearchContactsByWhere(?array $args, ?Collection $contacts): void
    {
        $this->repository
            ->shouldReceive('where')
            ->with($args ?? [])
            ->andReturn($contacts)
            ->once();

        $response = $this->service->findByArgs($args);

        $this->assertInstanceOf(Collection::class, $response);
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

    public static function findListResult(): array
    {
        $faker = Factory::create('pt_BR');

        return [
            'find contacts by specific argument' => [
                'args' => [
                    'first_name' => $firstName = $faker->firstName(),
                ],
                'contacts' => Contact::factory($faker->randomDigitNotNull())->make([
                    'first_name' => $firstName = $faker->firstName(),
                ]),
            ],
            'find contacts by some arguments' => [
                'args' => [
                    'first_name' => $firstName = $faker->firstName(),
                    'last_name' => $lastName = $faker->firstName(),
                    'company' => $companyName = $faker->company(),
                    'email' => $email = $faker->unique()->safeEmail(),
                ],
                'contacts' => Contact::factory($faker->randomDigitNotNull())->make([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'company' => $companyName,
                    'email' => $email,
                ]),
            ],
            'find contacts empty arguments' => [
                'args' => null,
                'contacts' => Contact::factory($faker->randomDigitNotNull())->make(),
            ],
        ];
    }
}