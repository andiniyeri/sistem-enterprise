<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Pesan kesalahan validasi dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'user_id wajib diisi.',
            'user_id.integer' => 'user_id harus berupa bilangan bulat.',
            'user_id.min' => 'user_id harus lebih besar dari 0.',
        ];
    }
}
