<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
        // Ignore the current client's own NIC when checking uniqueness on update
        $clientId = $this->route('client')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'nic'         => ['required', 'string', 'max:20', "unique:clients,nic,{$clientId}"],
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
