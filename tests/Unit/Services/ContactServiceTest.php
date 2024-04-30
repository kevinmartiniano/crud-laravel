<?php

namespace Tests\Unit\Services;

use App\DTO\ContactDTO;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Services\ContactService;
use Carbon\Carbon;
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

    #[DataProvider('findSimpleResult')]
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
    public function testShouldSearchContactsByWhere(array $args, ?Collection $contacts): void
    {
        $this->repository
            ->shouldReceive('where')
            ->with($args)
            ->andReturn($contacts)
            ->once();

        $response = $this->service->findByArgs($args);
        $this->assertInstanceOf(Collection::class, $response);
    }

    public function testShouldReceiveArrayArgumentsAndReturnContact(): void
    {
        $contact = Contact::factory()->make();

        $contactDTO = new ContactDTO(
            $contact->first_name,
            $contact->last_name,
            $contact->company,
            $contact->phone_number,
            $contact->mobile_number,
            $contact->email,
            Carbon::parse($contact->birth_date),
        );

        $this->repository
            ->shouldReceive('create')
            ->withArgs(function ($args) use ($contactDTO) {
                return $args->toArray() === $contactDTO->toArray();
            })
            ->andReturn($contact)
            ->once();

        $response = $this->service->createContact($contactDTO->toArray());
        $this->assertInstanceOf(Contact::class, $response);
    }

    public function testShouldReceiveFieldToUpdateAndReturnContact(): void
    {
        $oldPhoneNumber = $this->faker->phoneNumber();
        $contact = Contact::factory()->make(
            [
                'id' => $this->faker->randomNumber(2, true),
                'phone_number' => $oldPhoneNumber,
            ]
        );

        $updatedAt = $contact->updated_at;

        $this->repository
            ->shouldReceive('find')
            ->with($contact->id)
            ->andReturn($contact)
            ->once();

        $contactUpd = Contact::factory()->make($contact->toArray());
        $contactUpd->phone_number = $this->faker->phoneNumber();

        $updateValues = [
            'id' => $contact->id,
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->repository
            ->shouldReceive('update')
            ->withArgs(function ($model) use ($updateValues) {
                return
                    $model->phone_number === data_get($updateValues, 'phone_number') &&
                    $model->id === data_get($updateValues, 'id');
            })
            ->andReturn($contactUpd)
            ->once();

        $response = $this->service->updateContact($contact->id, $updateValues);

        $this->assertInstanceOf(Contact::class, $response);
        $this->assertNotSame($contact, $response);
        $this->assertNotSame($oldPhoneNumber, $response->phone_number);
    }

    public static function findSimpleResult(): array
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
                'args' => [],
                'contacts' => Contact::factory($faker->randomDigitNotNull())->make(),
            ],
        ];
    }
}
