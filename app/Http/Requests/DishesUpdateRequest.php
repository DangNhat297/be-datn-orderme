<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DishesUpdateRequest extends FormRequest
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
            'name' => 'required',
            'slug' => 'unique:dishes,slug,' . $this->dish,
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'category_id' => 'required',
            'image' => 'nullable',
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'result' => false,
            'message' => $validator->errors()->all()
        ],402);
        throw new ValidationException($validator, $response);
    }

}
