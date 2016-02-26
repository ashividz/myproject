<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PatientBTRequest extends Request
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
            'report_date' =>'required',
            'bt_report' =>'required|max:2000000|mimes:pdf,jpg,jpeg,png'
        ];
    }
}