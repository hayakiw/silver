<?php

namespace App\Http\Requests\Staff\User;

use App\Http\Requests\Request;
use App\Staff;

class UpdateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guard('staff')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => [
                'image',
            ],
            /*'name' => [
                'required',
                'max:50',
                'unique:staffs,name,NULL,id,canceled_at,NULL',
            ],*/
            'last_name' => [
                'required',
                'max:50',
            ],
            'first_name' => [
                'required',
                'max:50',
            ],
            'tel' => [
                'required',
                'numeric',
                'min:9',
                'min:11',
            ],
            'description' => [
                'required',
                'max:1000',
            ],
            'birth_at' => [
                'required',
                'date',
            ],
            'sex' => [
                'required',
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
            'image.image' => '"画像"はjpg,png,gifのいずれかを選択してください',
            'name.required' => '"ニックネーム"は必ず入力してください',
            'name.max' => '"ニックネーム"は:max文字以内で入力してください',
            'name.unique' => '入力した“ニックネーム”は既に登録されています',
            'last_name.required' => '"姓"は必ず入力してください',
            'last_name.max' => '"姓"は:max文字以内で入力してください',
            'first_name.required' => '"名"は必ず入力してください',
            'first_name.max' => '"名"は:max文字以内で入力してください',
            'tel.required' => '"電話番号"は必ず入力してください',
            'tel.numeric' => '"電話番号"は半角数字で入力してください',
            'tel.min' => '"電話番号"は正しく入力してください',
            'tel.max' => '"電話番号"は正しく入力してください',
            'area.required' => '"エリア"は必ず入力してください',
            'area.max' => '"エリア"は:max文字以内で入力してください',
            'description.required' => '"プロフィール"は必ず入力してください',
            'description.max' => '"プロフィール"は:max文字以内で入力してください',
            'birth_at.required' => '"生年月日"は必ず入力してください',
            'birth_at.date' => '"生年月日"は日付を入力してください',
            'sex.required' => '"性別"は必ず入力してください',
        ];
    }
}
