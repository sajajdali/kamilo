<?php

Route::group( [ 'middleware' => 'web', 'prefix' => 'admin' ], function () {
    Route::post( '/media', 'Modules\Media\Http\Controllers\MediaController@store' )->name('media.admin.file_store');
    Route::delete( '/media', 'Modules\Media\Http\Controllers\MediaController@delete' )->name('media.admin.file_delete');
    Route::patch( '/media', 'Modules\Media\Http\Controllers\MediaController@refresh' )->name('media.admin.refresh_list');
} );
