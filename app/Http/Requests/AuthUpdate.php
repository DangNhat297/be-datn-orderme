<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AuthUpdate extends FormRequest
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
            'phone' => 'required|min:11|numeric|unique:users,phone,' . $this->id,
            'password' => 'required|min:6',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'result' => false,
            'message' => $validator->errors()->all()
        ],402);
        throw new ValidationException($validator, $response);
    }
}
