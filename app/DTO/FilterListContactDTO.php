<?php

namespace App\DTO;

use App\DTO\Contracts\FilterListContactDTO as Contract;

class FilterListContactDTO implements Contract
{
    public function __construct(
        private ?int $id,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $company,
        private ?string $phoneNumber,
        private ?string $mobileNumber,
        private ?string $email
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'company' => $this->getCompany(),
            'phone_number' => $this->getPhoneNumber(),
            'mobile_number' => $this->getMobileNumber(),
            'email' => $this->getEmail(),
        ];
    }
}
