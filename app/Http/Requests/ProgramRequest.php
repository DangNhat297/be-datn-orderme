<?php

namespace App\Http\Requests;

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
                'date_format:YYYY-MM-DD H:mm:ss',
//                new CheckDateProgram()
            ],
            'end_date' => [
                'required',
                'date_format:YYYY-MM-DD H:mm:ss',
//                new CheckDateProgram()
            ],
            'discount_percent' => 'required_if:type,=,1|integer|min:0|max:100|',
            'status' => 'required|integer|in:0,1',
            'title' => 'required',
            'description' => 'required',
        ];

        if (request()->method() == 'PUT') {
            $rules = [
                'start_date' => [
                    'required',
                    'date_format:YYYY-MM-DD H:mm:ss',
                    Rule::unique('programs', 'start_date')
                        ->ignore(request()->route('program')->id)->where(function ($q) {
                            $q->where('start_date', '>=', request('start_date'))
                                ->where('end_date', '<=', request('end_date'));
                        })
                ]
            ];
            return $rules;
        }

        return $rules;
    }
}
