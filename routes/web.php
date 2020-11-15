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
    'prefix'        =>  config('admin.route.prefix')
], function (Router $router) {

    Route::get('utils/getResources', Controllers\UtilsController::class.'@getResources')->name('api.admin.utils.getResources');
    Route::get('utils/getCategories', Controllers\UtilsController::class.'@getCategories')->name('api.admin.utils.getCategories');
    Route::post('resource/postUpload', Controllers\UploadController::class.'@postUpload')->name('api.admin.resource.postUpload');
    Route::post('resource/destroy',Controllers\UploadController::class.'@destroy')->name('api.admin.resource.destroy');

});
