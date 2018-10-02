<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function store(UserRequest $request){
        $verifyData = \Cache::get($request->verification_key);
        if(!$verifyData){
            // 422 Unprocessable Entity - 用来表示校验错误
            return $this->response->error('验证码已失效', 422);
        }

        if(!hash_equals($verifyData['code'], $request->verification_code)){
            // 401 Unauthorized - 没有进行认证或者认证非法
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password)
        ]);

        \Cache::forget($request->verification_key);

        return $this->response->created();
    }
}
