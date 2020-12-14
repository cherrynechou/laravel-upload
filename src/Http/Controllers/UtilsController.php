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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * selected resource
 * Class UtilsController
 * @package Dcat\Admin\Extension\Upload\Http\Controllers
 */
class UtilsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResources(Request $request)
    {
        $cat_id = $request->input('cat_id') ?? 0;

        $user_id = request('user_id') ?? 0;

        $lists = Attachment::query()->where(function($query) use ($cat_id,$user_id){
            return $query->where('cat_id',$cat_id)->where('user_id',$user_id);
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

        return view('laravel-upload::resource',compact('lists','cat_id'));
    }

    /**
     * 加载分类
     */
    public function getCategories()
    {
        try {

            $user_id = request('user_id') ?? 0;


            $categories = AttachmentCategory::query()->where(function($query) use ($user_id){
                return $query->where('user_id',$user_id);
            })->all();
            
            return $this->ajaxJson(true,$categories,'200');
        }catch (\Exception $ex){
            return $this->ajaxJson(true,[],400,$ex->getMessage());
        }
    }

}