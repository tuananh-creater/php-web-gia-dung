<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Vui lòng chọn số sao.',
            'rating.integer' => 'Số sao không hợp lệ.',
            'rating.between' => 'Số sao phải từ 1 đến 5.',
            'content.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.',
        ];
    }
}