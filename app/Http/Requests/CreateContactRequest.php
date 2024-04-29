<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'string|required',
            'mobile_number' => 'string|required',
            'last_name' => 'string|nullable',
            'company' => 'string|nullable',
            'phone_number' => 'string|nullable',
            'email' => 'string|nullable',
            'birth_date' => 'date:nullable',
        ];
    }
}
