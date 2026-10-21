<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassportController extends Controller
{
    public function login(Request $request)
    {
        abort_unless($request->wantsJson(), 401);
        $username = $request->username;
        $password = $request->password;
        $response = array();
        $response['status'] = false;
        if ($username != null && $password != null) {
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $user = User::whereEmail($username);
            } else {
                $user = User::whereMobile($username);
            }
            if ($user->exists()) {
                $user = $user->first();
                if(Hash::check($password,$user->password)){
                    $response['status'] = true;
                    $response['token'] = $user->createToken("mobile_application_token")->accessToken;
                }else{
                    $response['message'] = "نام کاربری یا کلمه عبور صحیح نمی‌باشد.";
                }
            } else {
                $response['message'] = "نام کاربری یا کلمه عبور صحیح نمی‌باشد.";
            }
        } else {
            $response['message'] = "نام کاربری و کلمه عبور را وارد کنید.";
        }

        return response()->json($response,\Illuminate\Http\Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }
}
