<?php

Route::group( [ 'middleware' => 'web', 'prefix' => 'admin' ], function () {
    Route::group( [ 'prefix' => 'personalize' ], function () {
        /*
         * menu routes
         */
        Route::get( '/menu', 'Modules\Personalize\Http\Controllers\MenuController@index' )->name('personalize_index');
        Route::post( '/menu', 'Modules\Personalize\Http\Controllers\MenuController@store' );
        Route::get( '/menu/delete/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@delete' );
        Route::get( '/menu/edit/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@edit' );
        Route::put( '/menu/edit/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@update' );
        Route::patch( '/menu/edit/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@storeitem' );
        Route::delete( '/menu/edit/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@deleteItem' );
        Route::options( '/menu/edit/{menu}', 'Modules\Personalize\Http\Controllers\MenuController@updateItem' );
        Route::post( '/menu/getDetail', 'Modules\Personalize\Http\Controllers\MenuController@getDetail' );
        
        /**
         * options
         */
        Route::get( '/options', 'Modules\Personalize\Http\Controllers\OptionsController@index' );
    } );
} );
