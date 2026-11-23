<?php

/*
|--------------------------------------------------------------------------
| Auth General Routes
|--------------------------------------------------------------------------
|
| This route is general route for auth user.
| Please do not remove or edit this part.
|
*/

use Modules\Appointment\Entities\AppointmentUser;

Auth::routes();
Route::get('/login', 'Auth\LoginController@showLoginForm')
    ->middleware(['web', 'allowiframe'])
    ->name('login');

Route::post('/login', 'Auth\LoginController@login')
    ->middleware(['web', 'allowiframe']);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('queue/{token}', 'QueueController@execute');
Route::get('public',function (){
    return redirect()->route('login');
});
Route::get('public/login',function (){
    return redirect()->route('login');
});
Route::get('/shemiranWebLogin', function () {
    $User = \App\User::First();
    //$User = \App\User::find(192);
    //$User->update(['password'=> \Hash::make('22232907alireza')]);
    Auth::loginUsingId($User->id);
//    Auth::loginUsingId('389');
    if(auth()->check()){
        return redirect()->route('admin.dashboard');
    }else{
        return redirect()->route('login');
    }
});
Route::get('/test', function () {
    //$queue = \App\Queue::where('status' , '<>' , '0')->groupBy('content')->havingRaw('COUNT(*) > 1')->orderBy('id','DESC')->get();
//    $count = \Modules\Appointment\Entities\AppointmentUser::onlyTrashed()->update(['status' => '3']);
//    dd($count);

    smsNow( '09122978167',
        'kamiloCancel',
        "سجاد",
        "۱۴:۳۰",
        'kamio.ir'
    );

});

Route::get('/', function () {
    if(auth()->check()){
        return redirect()->route('admin.dashboard');
    }else{
        return redirect()->route('login');
    }
});
Route::name('admin.')->prefix('admin')->namespace('Admin')->middleware([
    'web',
    'permission:' . App\Base\AdminController::ADMIN_BASE_PERMMISION,
])->group(function () {
//    Route::get('dashboard', 'Dashboard\DashboardController@index')->name('dashboard');
    Route::get('dashboard', function(){
        return redirect()->route('admin.appointment_user.index');
    })->name('dashboard');
    Route::resource('role', 'Role\RoleController');
    Route::resource('user', 'User\UserController', array('except' => array('show')));
    Route::get('doc/{module}', 'Doc\DocController@index');
});

if (class_exists(\Modules\Profile\Http\Controllers\UserController::class)) {
    Route::get('user_recovery', '\Modules\Profile\Http\Controllers\UserController@recovery');
    Route::post('user_recovery', '\Modules\Profile\Http\Controllers\UserController@step2')->name('user.recovery.step1');
    Route::put('user_recovery', '\Modules\Profile\Http\Controllers\UserController@step3')->name('user.recovery.step2');
}


Route::get('/dd', function () {
    $appoint = Modules\Appointment\Entities\AppointmentUser::find(15530);
    $appointmentDetail = $appoint->detail;
    $appointmentDetail['name'] = 'sa';
    dd($appointmentDetail);
    //dd(jmktime(0, 0, 0, 6, 31, 1398));
   // dd(jdate('Y-m-d H:i:s',1569094201));
});

Route::get('json', 'HomeController@json');

Route::get('webservice',function(){
    dd(smsNow('09122978167' , 'kamiloAdd' , 'سجاد زمانی' , 'دوشنبه' , 'kamilo.ir'));
    dd(Auth::user()->roles->first()['name']);
    $api = new \Ghasedak\GhasedakApi(env('API_KEY_SMS'));
    dd($api->AccountInfo()->item);
});

Route::get('send-group-sms', 'QueueController@sendGroupSMS');
