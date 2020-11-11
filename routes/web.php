<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 17:03
 */

use CherryneChou\LaravelUpload\Http\Controllers;

Route::get('api/utils/getResources', Controllers\UtilsController::class.'@getResources')->name('api.utils.getResources');
Route::get('api/utils/getCategories', Controllers\UtilsController::class.'@getCategories')->name('api.utils.getCategories');
Route::post('api/resource/postUpload', Controllers\UploadController::class.'@postUpload')->name('api.resource.postUpload');
Route::post('api/resource/destroy',Controllers\UploadController::class.'@destroy')->name('api.resource.destroy');
