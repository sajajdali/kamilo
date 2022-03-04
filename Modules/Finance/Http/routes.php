<?php

Route::group([
    'as' => 'admin.finance.',
    'middleware' => ['web', 'permission:ADMIN'],
    'prefix' => 'admin/finance',
    'namespace' => 'Modules\Finance\Http\Controllers\Admin'
], function () {
    /* --- Transactions Route --- */
    Route::resource('transactions', 'TransactionController', ['except' => ['destroy']]);
});
