<?php

namespace App\Rules;

use App\Models\Program;
use Illuminate\Contracts\Validation\Rule;

class CheckDateProgram implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
    )
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Program::query()
                    ->where('start_date', '<=', $value)
                    ->where('end_date', '>=', $value)
                    ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
