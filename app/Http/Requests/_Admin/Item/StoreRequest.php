<?php

namespace App\Http\Requests\_Admin\Item;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'staff' => [
                'required',
            ],
            'category' => [
                'required',
            ],
            'title' => [
                'required',
                'max:255',
            ],
            'price' => [
                'required',
                'integer',
            ],
            'max_hours' => [
                'required',
                'integer',
            ],
            'location' => [
                'required',
                'max:255',
            ],
            'description' => [
                'required',
            ],
            'image' => [
                'file',
                'mimes:jpeg,bmp,png',
            ],
        ];
    }

    public function messages()
    {
        return [
            'staff.required' => 'スタッフは必ず入力してください',
            'title.required' => 'サービス名は必ず入力してください',
            'title.max' => ':max文字以内で入力してください',
            'category.required' => 'カテゴリは必ず選択してください',
            'price.required' => '時給はは必ず入力してください',
            'price.integer' => '数字で入力してください',
            'max_hours.required' => '最長時間は必ず入力してください',
            'max_hours.integer' => '数字で入力してください',
            'location.required' => '詳細な場所は必ず入力してください',
            'location.max' => ':max文字以内で入力してください',
            'description.required' => '詳細説明は必ず入力してください',
        ];
    }
}
