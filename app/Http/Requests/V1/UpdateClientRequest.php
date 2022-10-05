<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->method() == 'PUT') {
            return [
                'firstName' => 'required|alpha',
                'lastName' => 'required|alpha',
                'email' => 'required|email',
                'phoneNumber' => 'required|regex:/\+371[0-9]{8}/'
            ];
        } else {
            // PATCH request
            return [
                'firstName' => 'sometimes|required',
                'lastName' => 'sometimes|required',
                'email' => 'sometimes|required|email',
                'phoneNumber' => 'sometimes|required|regex:/\+371[0-9]{8}/'
            ];

        }

    }

    protected function prepareForValidation() {
        $merge = [];

        if ($this->firstName) {
            $merge['first_name'] = $this->firstName;
        }

        if ($this->lastName) {
            $merge['last_name'] = $this->lastName;
        }

        if ($this->phoneNumber) {
            $merge['phone_number'] = $this->phoneNumber;
        }

        $this->merge($merge);
    }
}
