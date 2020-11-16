<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 17:03
 */

use CherryneChou\LaravelUpload\Http\Controllers;
use Illuminate\Routing\Router;

Route::group([
    'prefix'        =>  config('admin.route.prefix'),
    'as'            => config('admin.route.prefix') . '.api.'
], function (Router $router) {

    Route::get('utils/getResources', Controllers\UtilsController::class.'@getResources')->name('utils.getResources');
    Route::get('utils/getCategories', Controllers\UtilsController::class.'@getCategories')->name('utils.getCategories');
    Route::post('resource/postUpload', Controllers\UploadController::class.'@postUpload')->name('resource.postUpload');
    Route::post('resource/destroy',Controllers\UploadController::class.'@destroy')->name('resource.destroy');

});
