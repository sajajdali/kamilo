<?php
Route::group( [
    'middleware' => [ 'web', 'permission:ADMIN' ],
    'prefix'     => 'admin',
    'namespace'  => 'Modules\Diet\Http\Controllers',
], function () {
    Route::get('/meal/test', 'Admin\Meal\MealController@test');
    Route::get('/meal/add', 'Admin\Meal\MealController@add');
    Route::get('/meal', 'Admin\Meal\MealController@index')->name('admin.meal.index');
    Route::put('/meal', 'Admin\Meal\MealController@groupchange');
    Route::post('/meal/add', 'Admin\Meal\MealController@store');
    Route::get('/meal/delete/{meal}', 'Admin\Meal\MealController@delete');
    Route::get('/meal/edit/{meal}', 'Admin\Meal\MealController@edit');
    Route::post('/meal/edit/{meal}', 'Admin\Meal\MealController@store');
//categories
    Route::get('/meal/category', 'Admin\Meal\CategoryController@index')->name('admin.meal_category.index');
    Route::post('/meal/category', 'Admin\Meal\CategoryController@store');
    Route::delete('/meal/category', 'Admin\Meal\CategoryController@deleteGroup');
    Route::get('/meal/category/delete/{catagory}', 'Admin\Meal\CategoryController@delete');
    Route::get('/meal/category/edit/{catagory}', 'Admin\Meal\CategoryController@edit');
    Route::post('/meal/category/edit/{catagory}', 'Admin\Meal\CategoryController@update');
    Route::get('/meal/category/changeStatus/{catagory}', 'Admin\Meal\CategoryController@changeStatus');
    Route::group( [
        'as'     => 'admin.'],function(){
        Route::resource('disease', 'Admin\Disease\DiseaseController', ['except' => ['show']]);
    });

    /*----------------- userDiet ----------------*/
    Route::get('/userDiet', 'Admin\Diet\UserDietController@index')->name('admin.user_diet.index');
    Route::get('/userDiet/delUnpaies', 'Admin\Diet\UserDietController@delete');
    Route::get('/userDiet/delete/{program}', 'Admin\Diet\UserDietController@deleteOneItem');
    /*----------------- offlineDiet ----------------*/
    Route::get('/offlineDiet', 'Admin\Diet\OfflineDietController@index')->name('admin.user_offline_diet.index');;
    Route::get('/offlineDiet/{program}', 'Admin\Diet\OfflineDietController@detail');
    Route::post('/offlineDiet/{program}', 'Admin\Diet\OfflineDietController@store');
    /*----------------- userDiet ----------------*/
    Route::resource('diet/package','Admin\DietPackage\DietPackageController',[
        'except' => 'show'
    ])->names([
        'index' => 'admin.diet.package.index' ,
        'create' => 'admin.diet.package.create',
        'edit' => 'admin.diet.package.edit',
        'destroy' => 'admin.diet.package.destroy',
        'store' => 'admin.diet.package.store',
        'update' => 'admin.diet.package.update',
    ]);
    Route::get('/diet/package/changeState/{package}', 'Admin\DietPackage\DietPackageController@changeState')->name('admin.diet.package.change');
});