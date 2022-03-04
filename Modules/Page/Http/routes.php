<?php
Route::group(['as'       => 'admin.',
              'middleware' => ['web','permission:'.App\Base\AdminController::ADMIN_BASE_PERMMISION],
              'prefix'     => 'admin',
              'namespace'  => 'Modules\Page\Http\Controllers\Admin'
], function() {
	Route::resource('pages', 'Page\PageController',  array('except' => array('show')));
});
