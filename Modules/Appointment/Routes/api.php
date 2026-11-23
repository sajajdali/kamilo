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
Route::get('appointment_today/tHSQPpCP6iuubWTMsxiN7Ra6k9TLwkvEahNFP9k5wotqKwz3dStS19AwMHDJcYdAwNEOXwE05vFpgzMWpQUXlx07XOkv9Ug2', 'Admin\AppointmentUserController@todayList');

// Call-center VoIP (Bearer token from `php artisan voip:token`)
Route::group([
    'prefix' => 'external-api/voip/v1',
    'namespace' => 'Voip',
], function () {
    Route::get('me', 'VoipAppointmentController@me');
    Route::get('appointments', 'VoipAppointmentController@appointments');
    Route::get('patients/by-phone/{phone}', 'VoipAppointmentController@byPhone')->where('phone', '.*');
});
