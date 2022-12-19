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
        $id = null
    ) {
        $this->id = $id;
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
            ->when($this->id, function ($q) {
                return $q->where('id', '<>', $this->id);
            })
            ->where(function ($q) use ($value, $attribute) {
                return $q->where('start_date', '<=', $value)
                    ->where('end_date', '>=', $value)
                    ->orWhereBetween($attribute, [request('start_date'), request('end_date')]);
            })
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
