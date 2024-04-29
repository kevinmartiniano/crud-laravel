<?php

namespace App\Repositories\Contracts;

use App\DTO\Contracts\ContactDTO;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepository
{
    /**
     * @param int $id record identifier
     * @return \App\Models\Contact|null
     */
    public function find(int $id): ?Contact;

    /**
     * @param array $data some arguments to find records
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function where(array $data): Collection;

    /**
     * @param array $data values to record create
     * @return \App\Models\Contact
     */
    public function create(ContactDTO $contact): Contact;
}