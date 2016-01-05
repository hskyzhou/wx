<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserAddUpdateRequest extends Request
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
            'name' => 'required|unique:users,name,'.request('id').',id|max:255',
            'email' => 'required|unique:users,email,'.request('id').',id|max:255|email',
            'password' => 'min:6',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'required' => ':attribute 不能为空.',
            'unique' => ':attribute 已经存在',
            'max' => ':attribute 最大不能超过 :max 个字符',
            'min' => ':attribute 不能小于 :min 个字符',
            'email' => ':attribute 不是合法的邮箱地址'
        ];

        return $messages;
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = [
            'name' => '姓名',
            'email' => '邮箱',
            'password' => '密码'
        ];

        return $attributes;
    }
}
