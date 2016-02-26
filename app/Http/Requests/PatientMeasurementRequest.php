<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PatientMeasurementRequest extends Request
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
            'arms'          =>  'numeric|min:0|max:999',
            'chest'         =>  'numeric|min:0|max:999',
            'waist'         =>  'numeric|min:0|max:999',
            'abdomen'       =>  'numeric|min:0|max:999',
            'thighs'        =>  'numeric|min:0|max:999',
            'hips'          =>  'numeric|min:0|max:999',
            'bp_systolic'   =>  'integer|min:0|max:999',
            'bp_diastolic'  => 'integer|min:0|max:999',
            'waist'          =>  'required_without_all:arms,chest,abdomen,thighs,hips,bp_systolic,bp_diastolic',
        ];
    }
}