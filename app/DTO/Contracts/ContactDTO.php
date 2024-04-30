<?php

namespace App\DTO\Contracts;

use Carbon\Carbon;

interface ContactDTO
{
    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function getCompany(): ?string;

    public function getPhoneNumber(): ?string;

    public function getMobileNumber(): ?string;

    public function getEmail(): ?string;

    public function getBirthDate(): ?Carbon;

    public function toArray(): array;
}
