<?php

namespace App\Http\Requests;

use App\Rules\CheckDateProgram;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
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
            'start_date' => [
                'required',
            //    new CheckDateProgram()
            ],
            'end_date' => [
                'required',
            //    new CheckDateProgram()
            ],
            'discount_percent' => 'required_if:type,=,1|integer|min:0|max:100|',
            'status' => 'required|integer|in:0,1',
            'title' => 'required',
            'description' => 'required',
            'dish_ids' => 'required|array|min:1',
            'dish_ids.*' => 'exists:dishes,id'
        ];

        if (request()->method() == 'PUT') {
            
            return $rules;
        }

        return $rules;
    }
}
