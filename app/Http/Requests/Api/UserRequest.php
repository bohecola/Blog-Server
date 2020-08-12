<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
        case 'POST':
            return [
                'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                'phone' => 'required|unique:users,phone',
                'password' => 'required|alpha_dash|min:6',
                'verification_key' => 'required|string',
                'verification_code' => 'required|string',
            ];
            break;
        case 'PATCH':
            $userId = auth('api')->id();
            return [
                'name' => 'between:3,25|regex:/^[\x7f-\xffA-Za-z0-9\-\_]+$/|unique:users,name,'.$userId,
                'introduction' => 'max:80',
                'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
            ];
            break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信验证码 key',
            'verification_code' => '短信验证码',
        ];
    }
    public function messages()
    {
        return [
            'phone.unique' => '该手机号码已被注册',
            'verification_key.required' => '请填写正确的验证码',
            'name.unique' => '用户名已被占用，请重新填写',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空',
        ];
    }
}
