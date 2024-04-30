<?php

namespace App\Repositories;

use App\DTO\Contracts\ContactDTO;
use App\Models\Contact;
use App\Repositories\Contracts\ContactRepository as Contract;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository implements Contract
{
    public function find(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function where(array $data): Collection
    {
        return Contact::where($data)
            ->get();
    }

    public function create(ContactDTO $contact): Contact
    {
        return Contact::create($contact->toArray());
    }

    public function update(Contact $contact): Contact
    {
        $contact->save();

        return $contact;
    }
}