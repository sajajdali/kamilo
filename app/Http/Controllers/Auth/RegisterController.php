<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile' => 'required|max:255|unique:users',
            'email' => 'max:255|unique:users',
            'password' => 'required|min:4|confirmed',
        ],[
            'mobile.unique' => 'این شماره موبایل قبلا در سایت ثبت شده است',
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است',
            'mobile.max' => 'شماره موبایل صحیح نمی‌باشد.',
            'email.max' => 'ایمیل وارده صحیح نمی‌باشد',
            'password.confirmed' => 'کلمه عبور و تکرار آن مطابقت ندارد',
            'password.min' => 'کلمه عبور حداقل می‌بایست ۴ کاراکتر باشد',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $roleDefault = Role::whereIsDefault(true)->first()->id;
        $user = User::create([
            'mobile' => latin_num($data['mobile']),
            'email' => ($data['email'] != "" ? $data['email'] : latin_num($data['mobile'])),
            'password' => Hash::make($data['password']),
        ]);
        $user->roles()->sync($roleDefault);
        return $user;
    }
}
