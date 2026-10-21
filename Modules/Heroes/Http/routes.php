<?php

Route::group([
    'as' => 'admin.',
    'middleware' => ['web', 'permission:ADMIN'],
    'prefix' => 'admin',
    'namespace' => 'Modules\Heroes\Http\Controllers\Admin',
], function () {
    Route::resource('heroes', 'HeroesController', ['except' => 'show']);
});