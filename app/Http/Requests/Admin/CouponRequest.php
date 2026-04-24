<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('coupons', 'code')->ignore($couponId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:fixed,percent'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'expired_at' => ['nullable', 'date'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('discount_type') === 'percent' && $this->input('discount_value') > 100) {
                $validator->errors()->add('discount_value', 'Giảm theo phần trăm không được vượt quá 100.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề mã giảm giá.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'expired_at.date' => 'Ngày hết hạn không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}