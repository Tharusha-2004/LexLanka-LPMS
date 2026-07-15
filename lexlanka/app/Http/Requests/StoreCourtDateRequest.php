<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourtDateRequest extends FormRequest
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
            'case_id' => ['required', 'integer', 'exists:legal_cases,id'],
            'date'    => ['required', 'date', 'after:now'],
            'type'    => ['required', 'string', 'in:calling_date,trial_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'case_id.required' => 'Please select a case.',
            'case_id.exists'   => 'The selected case does not exist.',
            'date.required'    => 'A date and time is required.',
            'date.date'        => 'Please enter a valid date and time.',
            'date.after'       => 'The court date must be a future date and time.',
            'type.required'    => 'Please select a date type.',
            'type.in'          => 'The date type must be either Calling Date or Trial Date.',
        ];
    }
}
