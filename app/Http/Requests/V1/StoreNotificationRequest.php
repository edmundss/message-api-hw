<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MessageLength;

class StoreNotificationRequest extends FormRequest
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
        return [
            'clientId' => 'required|uuid|exists:clients,id',
            'channel' => ['required', Rule::in(['sms', 'email'])],
            'message' => ['required', 'string', new MessageLength],
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'client_id' => $this->clientId,
            'status' => 'pending'
        ]);
    }
}
