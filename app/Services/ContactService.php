<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepository;

class ContactService
{
    public function __construct(
        private ContactRepository $contactRepository
    )
    {
    }

    public function findContact(int $id): ?Contact
    {
        return $this->contactRepository->find($id);
    }
}