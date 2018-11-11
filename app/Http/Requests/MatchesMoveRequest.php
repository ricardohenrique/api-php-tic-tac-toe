<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchesMoveRequest extends FormRequest
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
            'position' => 'required|integer|between:0,8'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'position.required' => 'position-is-required',
            'position.integer'  => 'position-must-be-an-integer',
            'position.between'  => 'position-must-be-between-:min-:max'
        ];
    }
}
