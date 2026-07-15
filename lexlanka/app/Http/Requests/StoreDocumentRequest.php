<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'case_id'  => ['required', 'integer', 'exists:legal_cases,id'],
            'file'     => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:25600', // 25 MB in kilobytes
            ],
            'category' => ['required', 'string', 'in:evidence,deeds,correspondence'],
        ];
    }

    public function messages(): array
    {
        return [
            'case_id.required'  => 'Please select a case.',
            'case_id.exists'    => 'The selected case does not exist.',
            'file.required'     => 'Please select a file to upload.',
            'file.file'         => 'The upload must be a valid file.',
            'file.mimes'        => 'Only PDF, JPG, and PNG files are allowed.',
            'file.max'          => 'The file may not be larger than 25 MB.',
            'category.required' => 'Please select a document category.',
            'category.in'       => 'Category must be Evidence, Deeds, or Correspondence.',
        ];
    }
}
