<?php

Route::group( [
    'as'         => 'admin.',
    'middleware' => [ 'web', 'permission:ADMIN' ],
    'prefix'     => 'admin',
    'namespace'  => 'Modules\Blog\Http\Controllers\Admin\Blog',
], function () {
    /* News Handler */
    
    Route::resource( 'blog', 'BlogController', [ 'except' => 'show' ] );
    Route::group( [
        'as'     => 'blog.',
        'prefix' => 'blog',
    ], function () {
        Route::put( '/', 'BlogController@changeGroup' )->name( 'changeGroup' );
        Route::get( 'tag', 'BlogController@tags' )->name( 'tag' );
        Route::post( 'upload', 'BlogController@upload' )->name( 'upload' );
        Route::post( 'remove-image', 'BlogController@removeImage' )->name( 'removeImage' );
        Route::get( 'change-status/{blog}', 'BlogController@changeStatus' )->name( 'changeStatus' );
        
        /* Categories */
        
        Route::resource( 'category', 'CategoryController', [ 'except' => 'show' ] );
        Route::group( [
            'as'     => 'category.',
            'prefix' => 'category',
        ], function () {
            Route::delete( 'group', 'CategoryController@deleteGroup' )->name( 'deleteGroup' );
        } );
        
        /* Comments */
        
        Route::resource( 'comments', 'CommentController', [ 'only' => [ 'index', 'update', 'edit','destroy' ] ] );
        Route::put( 'comments', 'CommentController@changeGroup' )->name( 'comments.changeGroup' );
        Route::get( 'comments/change_status/{comment}/{status}', 'CommentController@changeStatus' )->name( 'comments.changeStatus' );
    } );
    
} );

Route::group( [
    'prefix' => 'blog',
    'namespace'  => 'Modules\Frontend\Http\Controllers\blog',
], function () {
    Route::resource( 'posts', 'BlogController', [ 'except' => ['edit','store','destroy','create','update'] ] );
} );
