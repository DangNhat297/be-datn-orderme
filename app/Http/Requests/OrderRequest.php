<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        return [
            'location_id' => 'required|exists:locations,id',
            'total' => 'required|integer',
            'price_sale' => 'nullable|integer|min:0',
            'price_none_sale' => 'required|integer',
            'coupon_id' => [
                'nullable',
                'integer',
                Rule::exists('coupons', 'id')
                    ->where(fn ($q) =>
                    $q->where('quantity', '>', 0)
                        ->where('status', ENABLE))
            ]
        ];
    }
}
