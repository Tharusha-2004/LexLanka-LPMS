<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'nic'         => ['required', 'string', 'max:20', 'unique:clients,nic'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'intake_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Client name is required.',
            'nic.required'         => 'NIC number is required.',
            'nic.unique'           => 'A client with this NIC already exists.',
            'intake_date.required' => 'Intake date is required.',
        ];
    }
}
