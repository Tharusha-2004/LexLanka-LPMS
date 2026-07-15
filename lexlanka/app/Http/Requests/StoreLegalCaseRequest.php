<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegalCaseRequest extends FormRequest
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
            'client_id'            => ['required', 'integer', 'exists:clients,id'],
            'assigned_attorney_id' => ['required', 'integer', 'exists:users,id'],
            'case_type'            => ['required', 'string', 'max:255'],
            'status'               => [
                'required',
                'string',
                'in:pending,active,trial_scheduled,judgment_delivered,case_closed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required'            => 'Please select a client.',
            'client_id.exists'              => 'The selected client does not exist.',
            'assigned_attorney_id.required' => 'Please assign an attorney.',
            'assigned_attorney_id.exists'   => 'The selected attorney does not exist.',
            'case_type.required'            => 'Case type is required.',
            'status.required'               => 'Please select a status.',
            'status.in'                     => 'The selected status is invalid.',
        ];
    }
}
