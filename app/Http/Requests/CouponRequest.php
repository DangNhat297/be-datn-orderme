<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'coupon' => [
                'required',
                Rule::unique('coupons', 'coupon')->where(function ($q) {
                    return $q->whereBetween('start_date', [request('start_date'), request('end_date')])
                        ->orWhereBetween('end_date', [request('start_date'), request('end_date')]);
                })
            ],
            'type' => 'required|integer|in:1,2',
            'discount_percent' => 'required_if:type,=,1|integer|min:0|max:100|',
            'discount_price' => 'required_if:type,=,2|integer',
            'quantity' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];

        if (request()->method() == 'PUT') {
            $rules['coupon'] = [
                'required',
                Rule::unique('coupons', 'coupon')
                    ->ignore(request()->route('coupon')->id)
                    ->where(function ($q) {
                        $q->whereBetween('start_date', [request('start_date'), request('end_date')])
                            ->orWhereBetween('end_date', [request('start_date'), request('end_date')]);
                    })
            ];

            return $rules;
        }

        return $rules;
    }
}
