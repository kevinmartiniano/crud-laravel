<?php

namespace App\DTO\Contracts;

interface FilterListContactDTO
{
    public function getId(): ?int;

    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function getCompany(): ?string;

    public function getPhoneNumber(): ?string;

    public function getMobileNumber(): ?string;

    public function getEmail(): ?string;

    public function toArray(): array;
}
