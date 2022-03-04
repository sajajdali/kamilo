<?php
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'admin',
    'namespace' => 'Admin',//prefix module/Appointment add automatic
], function () {
    Route::resource('appointment_part', 'AppointmentPartController', ['except' => 'edit']);
    Route::resource('appointment', 'AppointmentController');
    Route::apiResource('appointment_sms', 'AppointmentSmsController', ['except' => 'show']);
        Route::get('appointment_detail', 'AppointmentController@detail');
});