<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class Checkuser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (User::find($value) == []) {
            $fail(':attribute does not exist');
        } elseif (User::find($value)->id == Auth::id()) {
            $fail('Invalid command you cannot send to yourself, check receiver field');
        }
    }
}