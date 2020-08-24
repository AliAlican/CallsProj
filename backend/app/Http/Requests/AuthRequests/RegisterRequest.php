<?php

namespace App\Http\Requests\AuthRequests;

use App\Http\Requests\ApiRequest;

//CANT USED BECAUSE WE RECEIVE A STRANGE OAUTH2 ERROR ON REGISTER
class RegisterRequest extends ApiRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'password' => 'string|required',
            'email' => 'required|email:rfc,dns|unique:users,email'
        ];
    }
}
