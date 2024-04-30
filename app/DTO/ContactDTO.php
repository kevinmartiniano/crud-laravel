<?php

namespace App\DTO;

use App\DTO\Contracts\ContactDTO as Contract;
use Carbon\Carbon;

class ContactDTO implements Contract
{
    public function __construct(
        private ?string $firstName,
        private ?string $lastName,
        private ?string $company,
        private ?string $phoneNumber,
        private ?string $mobileNumber,
        private ?string $email,
        private ?Carbon $birthDate
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getBirthDate(): ?Carbon
    {
        return $this->birthDate;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'company' => $this->getCompany(),
            'phone_number' => $this->getPhoneNumber(),
            'mobile_number' => $this->getMobileNumber(),
            'email' => $this->getEmail(),
            'birth_date' => $this->getBirthDate()?->toDateString(),
        ];
    }
}
