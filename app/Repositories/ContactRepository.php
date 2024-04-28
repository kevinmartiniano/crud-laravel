<?php

namespace App\Repositories;

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
}