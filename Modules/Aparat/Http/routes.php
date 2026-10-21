<?php

Route::group([
    'as' => 'admin.',
    'middleware' => ['web', 'permission:ADMIN'],
    'prefix' => 'admin',
    'namespace' => 'Modules\Aparat\Http\Controllers\Admin',
], function () {
    Route::resource('aparat', 'AparatController', ['except' => 'show']);
});
Route::get( "/aparat/{aparat}", "Modules\Frontend\Http\Controllers\Video\VideoController@show" )->name( 'frontend.videos.show' );