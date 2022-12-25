<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryUpdateRequest extends FormRequest
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
            'slug'  => 'required|unique:dish_categories,slug,' . $this->category,
            // 'image' => 'nullable|image'
            // 'parent_id' => [
            //     'required',
            //     'integer',
            //     Rule::when($this->parent_id != 0, function () {
            //         return Rule::exists('dish_categories', 'id')->where('parent_id', 0);
            //     })
            // ]
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
