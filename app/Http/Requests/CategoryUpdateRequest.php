<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'parent_id' => [
                'required',
                'integer',
                Rule::when($this->parent_id != 0, function () {
                    return Rule::exists('dish_categories', 'id')->where('parent_id', 0);
                })
            ]
        ];
    }
}
