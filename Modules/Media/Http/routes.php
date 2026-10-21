<?php

Route::group( [ 'middleware' => 'web', 'prefix' => 'admin' ], function () {
    Route::post( '/media', 'Modules\Media\Http\Controllers\mediaController@store' )->name('media.admin.file_store');
    Route::delete( '/media', 'Modules\Media\Http\Controllers\mediaController@delete' )->name('media.admin.file_delete');
    Route::patch( '/media', 'Modules\Media\Http\Controllers\mediaController@refresh' )->name('media.admin.refresh_list');
} );
