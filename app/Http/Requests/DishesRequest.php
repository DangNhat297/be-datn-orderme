<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DishesRequest extends FormRequest
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
            'slug' => 'required|unique:dishes,slug,'.$this->dishes,
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'category_id' => 'required',
//            'image' => 'image',
        ];
    }


        protected  function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()
        ];
        $response = response( $json );
        throw new ValidationException($validator, $response);
    }

}
