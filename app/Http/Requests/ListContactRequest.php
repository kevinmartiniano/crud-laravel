<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->merge([
            'id' => $this->get('id'),
            'first_name' => $this->get('first_name'),
            'last_name' => $this->get('last_name'),
            'company' => $this->get('company'),
            'phone_number' => $this->get('phone_number'),
            'mobile_number' => $this->get('mobile_number'),
            'email' => $this->get('email'),
        ]);

        return [
            'id' => 'integer|nullable',
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'company' => 'string|nullable',
            'phone_number' => 'string|nullable',
            'mobile_number' => 'string|nullable',
            'email' => 'string|nullable',
        ];
    }
}
