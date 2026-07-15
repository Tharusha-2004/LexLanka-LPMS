<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLedgerEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_id'     => ['required', 'integer', 'exists:legal_cases,id'],
            'type'        => ['required', 'string', 'in:trust,operational'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'case_id.required'     => 'Please select a case.',
            'type.required'        => 'Please select a ledger type.',
            'amount.min'           => 'Amount must be at least 0.01.',
            'description.required' => 'A description is required.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $description = $this->input('description', '');
            $type = $this->input('type');
            if ($type === 'operational' && stripos($description, 'retainer') !== false) {
                $validator->errors()->add('type', 'Retainer funds must be recorded in the Client Trust Ledger.');
            }
        });
    }
}
