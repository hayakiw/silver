<?php

namespace App\Http\Requests\Staff\User;

use App\Http\Requests\Request;
use App\Staff;

class StoreRequest extends Request
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
            'name' => [
                'required',
                'max:50',
            ],
            'prefecture' => [
                'required',
                'max:50',
            ],
            'area' => [
                'required',
                'max:50',
            ],
            'description' => [
                'required',
                'max:1000',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:staffs,email,NULL,id,canceled_at,NULL',
            ],
            'password' => [
                'required',
                'between:6,20',
                'ascii',
            ],
            'service.name' => [
                'required',
            ],
            'service.category' => [
                'required',
            ],
            'service.price' => [
                'required',
            ],
            'service.description' => [
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
            'name.required' => '"名前"は必ず入力してください',
            'name.max' => '"名前"は:max文字以内で入力してください',
            'area.required' => '"エリア"は必ず入力してください',
            'area.max' => '"エリア"は:max文字以内で入力してください',
            'description.required' => '"プロフィール"は必ず入力してください',
            'description.max' => '"プロフィール"は:max文字以内で入力してください',
            'email.required' => '"メールアドレス"は必ず入力してください',
            'email.email' => '"メールアドレス"を正しく入力してください',
            'email.max' => '“メールアドレス”は:max文字以内で入力してください',
            'email.unique' => '入力した“メールアドレス”は既に登録されています',
            'password.required' => '“パスワード"は必ず入力してください',
            'password.between' => '"パスワード"は:min〜:max文字で入力してください',
            'password.ascii' => '"パスワード"を正しく入力してください',
            'service.name.required' => '"サービス名"は必ず入力してください',
            'service.category.required' => '"カテゴリ"は必ず入力してください',
            'service.price.required' => '"時給"は必ず入力してください',
            'service.description.required' => '"詳細説明"は必ず入力してください',
        ];
    }
}
