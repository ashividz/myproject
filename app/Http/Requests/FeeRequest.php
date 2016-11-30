<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FeeRequest extends Request
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
            'entry_date'            =>  'required|date',
            'start_date'            =>  'required|date',
            'end_date'              =>  'required|date',
            'duration'              =>  'required',
            'amount'                =>  'required',
            'cre'                   =>  'required',
            'source'                =>  'required'
        ];
    }
}