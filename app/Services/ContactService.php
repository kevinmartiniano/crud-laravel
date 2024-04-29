<?php

namespace App\Services;

use App\DTO\FilterListContactDTO;
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
        $filter = new FilterListContactDTO(
            data_get($args, 'id'),
            data_get($args, 'first_name'),
            data_get($args, 'last_name'),
            data_get($args, 'company'),
            data_get($args, 'phone_number'),
            data_get($args, 'mobile_number'),
            data_get($args, 'email')
        );

        $hidratedFilters = array_filter($filter->toArray(), function ($value) {
            return !is_null($value) ? true : false;
        }, ARRAY_FILTER_USE_BOTH);

        return $this->contactRepository->where($hidratedFilters);
    }
}