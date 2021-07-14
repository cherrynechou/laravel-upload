<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 17:54
 */
namespace CherryneChou\LaravelUpload\Http\Controllers;

use CherryneChou\LaravelUpload\Models\Attachment;
use CherryneChou\LaravelUpload\Models\AttachmentCategory;
use Illuminate\Support\Facades\Storage;

/**
 * selected resource
 * Class UtilsController
 * @package Dcat\Admin\Extension\Upload\Http\Controllers
 */
class UtilsController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResources()
    {
        $cat_id = request('cat_id') ?? 0;  //分类

        $user_id = request('user_id') ?? 0;  //用户或者 商家

        $module_name = request('module_name') ?? '';

        $lists = Attachment::query()->when($cat_id,function($query) use ($cat_id){
          return $query->where('cat_id',$cat_id);
        })->when($user_id,function($query) use ($user_id){
            return $query->where('user_id',$user_id);
        })->when($app_name,function($query) use ($module_name){
            return $query->where('module_name',$module_name);
        })->orderBy('created_at','DESC')->paginate(10);

        //处理数据
        $resources = $lists->items();

        $storageName = config('upload.disk');

        collect($resources)->map(function ($item) use ($storageName){
            $exists = Storage::disk($storageName)->exists($item->url);
            if($exists){
                $item['remote_url'] = Storage::disk($storageName)->url($item->url);
            }else{
                $item['remote_url'] = '';
            }
            return $item;
        });

        return view('laravel-upload::resource',compact('lists', 'cat_id', 'module_name'));
    }

    /**
     * 加载分类
     */
    public function getCategories()
    {
        try {

            $app_name = request('app_name') ?? '';

            $categories = AttachmentCategory::query()->where(function($query) use ($app_name){
                return $query->where('app_name',$app_name);
            })->get();

            return $this->ajaxJson(true,$categories,'200');
        }catch (\Exception $ex){
            return $this->ajaxJson(true,[],400,$ex->getMessage());
        }
    }

}
