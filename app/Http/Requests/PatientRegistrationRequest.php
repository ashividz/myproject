<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PatientRegistrationRequest extends Request
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
            'email'         =>  'required|email|unique:marketing_details,email,'.$this->get('id'),
            'phone'         =>  'required|unique:marketing_details,phone,'.$this->get('id'),
            'address'       =>  'required',
            'country'       =>  'required',
            'state'         =>  'required',
            'city'          =>  'required',
        ];
    }
}