<?php

namespace App\Rules\api;

use App\Models\FamilyMember;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CheckAccountPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $account ;
    public function __construct($MemberID)
    {
        $this->account = FamilyMember::find($MemberID)->sponser->account;
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
        //

        if(!Hash::check($value,$this->account->password))
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid Password';
    }
}
