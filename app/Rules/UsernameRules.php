<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsernameRules implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    private $email;
    public function __construct($email)
    {
        $this->email = $email;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userExusts = User::where('username', $value)->exists();
        if (!$userExusts) {
            $emailExists = User::where('email', $this->email)->exists();
            if(!$emailExists){
                $fail('The ' . $attribute . ' does not exists.');
                return;
            }
            $fail('The email exist but ' . $attribute . ' does not exists.');
        }
    }
}
