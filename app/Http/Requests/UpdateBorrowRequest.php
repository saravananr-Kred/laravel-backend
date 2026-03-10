<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'exists:books,id',
            'member_id' => 'exists:members,id',
            'issue_date' => 'date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|string|in:borrowed,returned',
        ];
    }
}
