<?php

Route::group( [ 'middleware' => 'web', 'prefix' => 'admin' ], function () {
    Route::get( '/options', 'Modules\Options\Http\Controllers\OptionsController@index' )->name('admin.options_show');
    Route::post( '/options', 'Modules\Options\Http\Controllers\OptionsController@update' )->name('admin.options_update');
} );
