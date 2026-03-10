<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'string|max:255',
            'author' => 'string|max:255',
            'ISBN' => 'string|unique:books,ISBN,' . $this->book->id,
            'category' => 'string|max:255',
            'published_year' => 'integer|min:1000|max:' . date('Y'),
            'available_copies' => 'integer|min:0',
        ];
    }
}
