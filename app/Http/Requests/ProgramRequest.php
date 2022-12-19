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
                'check_date_program'
            ],
            'end_date' => [
                'required',
                'check_date_program',
                'after:start_date'
            ],
            'status' => 'required|integer|in:0,1',
            'title' => 'required',
            'description' => 'required',
            'dish_ids' => 'required|array|min:1',
            'dish_ids.*.dish_id' => 'required|exists:dishes,id'
        ];

        if (request()->method() == 'PUT') {
            $rules['start_date'] = [
                'required',
                'check_date_program:' . request()->route()->parameter('program')->id
            ];
            $rules['end_date'] = [
                'required',
                'check_date_program:' . request()->route()->parameter('program')->id,
                'after:start_date'
            ];

            return $rules;
        }
        return $rules;
    }
}
