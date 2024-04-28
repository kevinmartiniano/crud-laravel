<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepository;
use Illuminate\Database\Eloquent\Collection;

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

    public function findByArgs(array $args = []): ?Collection
    {
        return $this->contactRepository->where($args);
    }
}