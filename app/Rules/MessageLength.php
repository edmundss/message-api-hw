<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class MessageLength implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $channel = request()->channel;

        if (!$channel) {
            $index = explode('.',$attribute)[0];
            $channel = request()->all()[$index]['channel'];
        }

        if($channel == 'sms' && strlen($value) > 140){
            return $fail('Maximum message length for SMS channel is 140.');
        }
    }
}
