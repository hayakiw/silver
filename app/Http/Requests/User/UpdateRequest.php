<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\User;

class UpdateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:50',
            ],
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '“姓"は必ず入力してください',
            'name.max' => '“姓"は:max文字以内で入力してください',
        ];
    }
}
