<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\ISO6346Validator;

class ISO6346ContainerNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ISO6346Validator::validateContainerNumber($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid ISO 6346 container number with correct check digit.';
    }
}