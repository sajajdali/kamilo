<?php
Route::group([
    'as' => 'admin.',
    'middleware' => ['web', 'permission:ADMIN'],
    'prefix' => 'admin',
    'namespace' => 'Admin',//prefix module/Appointment add automatic
], function () {
    Route::resource('appointment_part', 'AppointmentPartController', ['except' => 'show']);
    Route::get('appointment/add','AppointmentController@add')->name('appointment.add');
    Route::get('appointment/change','AppointmentController@changeTime')->name('appointment.change');
    Route::resource('appointment', 'AppointmentController', ['except' => 'show']);
    Route::resource('appointment_sms', 'AppointmentSmsController', ['except' => 'show']);
    Route::resource('appointment_user', 'AppointmentUserController', ['except' => 'show']);

    //standard sms
    Route::get('standard_sms/{appointment}','AppointmentUserController@sendStandardSms')->name('standard_sms');
    Route::post('standard_sms/{appointment}','AppointmentUserController@sendStandardSmsStore')->name('standard_sms.send');
    //standard sms
    //ajax appointment routes
    Route::post('appointment/calenderhelper','AppointmentController@calenderHelper');
    Route::post('appointment/exceptionhelper','AppointmentController@exceptionHelper');
    Route::post('appointment/ajax/gettimes','AppointmentController@ajaxgettime');
    Route::post('appointment/ajax/getappointment','AppointmentController@getappointment');
    Route::post('appointment/ajax/updateTime','AppointmentController@storeAppointmentUpdateTime');
    Route::post('appointment/ajax/getajaxexception', 'AppointmentController@getajaxexception');
    Route::post('appointment/ajax/loadMonth', 'AppointmentController@loadMonth');
    Route::post('appointment/ajax/getUser', 'AppointmentController@getUser');
    Route::post('appointment/ajax/storeAppointment', 'AppointmentController@storeAppointment');
    Route::post('appointment/ajax/changeAction', 'AppointmentController@changeAction');
    Route::post('appointment/ajax/checkAppointment', 'AppointmentController@checkAppointment');
    Route::get('statusSms', 'AppointmentController@statusSms')->name('status.result');
    Route::get('appointment/report', 'AppointmentController@report')->name('report.index');
    Route::get('appointment/report/create', 'AppointmentController@reportCreate')->name('report.create');
    Route::post('appointment/report/store', 'AppointmentController@reportStore')->name('report.store');

    // black list
    Route::get('black_list', [\Modules\Appointment\Http\Controllers\Admin\BlackListController::class, 'index'])->name('blackList.index');
    Route::delete('black_list/{black_list}', [\Modules\Appointment\Http\Controllers\Admin\BlackListController::class, 'delete'])->name('blackList.destroy');
    // black list

});

Route::group([
    'as' => 'admin.',
    'middleware' => ['web'],
    'prefix' => 'admin',
    'namespace' => 'Admin',//prefix module/Appointment add automatic
], function () {
    Route::get('appointment/old', 'AppointmentOldController@index');
    Route::post('appointment/ajax/checkAppointmentChangeTime', 'AppointmentController@checkAppointmentChangeTime');

});

Route::group([
    'as' => 'admin.',
    'middleware' => ['web'],
    'prefix' => 'admin',
    'namespace' => 'Admin',//prefix module/Appointment add automatic
], function () {
    Route::get('appointment/old', 'AppointmentOldController@index');

});

Route::group([
    'as' => 'profile.',
    'middleware' => ['web'],
    'prefix' => 'appointment',
    'namespace' => 'Profile',//prefix module/Appointment add automatic
], function () {

    Route::get('dd',function(){
        $shorterLink = \App\Http\Controllers\shortURLController::ShortLinkMaker('http://wide.ir/Pages/Default.aspx#');
        dd($shorterLink);
    });

    Route::get('result/cancel/{code}/{id}', 'AppointmentResultController@cancel')->name('appointment.result.cancel');
    Route::get('result/{code}/{id}', 'AppointmentResultController@result')->name('appointment.result.show');

});


