<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'phone_number',
        'mobile_number',
        'mail',
        'birth_date',
    ];
}
