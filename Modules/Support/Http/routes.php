<?php

Route::group(['as' => 'admin.',
              'middleware' => ['web', 'permission:ADMIN'],
              'prefix' => 'admin',
              'namespace' => 'Modules\Support\Http\Controllers\Admin\Support'
], function()
{
    /* support Handler */
    
    Route::group(['as' => 'support.',
                  'prefix' => 'support'
    ], function () {
        
        /* Parts */
        Route::resource('parts', 'PartController',  ['except' => ['show', 'create']]);
        Route::get('/parts/change-status/{part}', 'PartController@changeStatus')->name('parts.changeStatus');
        Route::get('/parts/users/{part}', 'PartController@users')->name('parts.users');
        Route::post('/parts/users/{part}', 'PartController@updateUser')->name('parts.updateUser');
        
        /* Tickets */
        Route::resource('tickets', 'TicketController',  ['except' => ['show', 'create', 'store']]);
        Route::get('/tickets/change-status/{ticket}', 'TicketController@changeStatus')->name('tickets.changeStatus');
        
        /* Questions */
        Route::resource('questions', 'QuestionController',  ['except' => ['show']]);

        Route::resource('/message','MessageController',  ['except' => ['show', 'update','edit']]);
    });
    
});