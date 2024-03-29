<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 17:59
 */
namespace CherryneChou\LaravelUpload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use CherryneChou\LaravelUpload\Events\Uploaded;
use CherryneChou\LaravelUpload\Traits\HasFileUpload;
use CherryneChou\LaravelUpload\Models\Attachment;
use CherryneChou\LaravelUpload\Models\AttachmentCategory;
use Illuminate\Support\Facades\DB;

/**
 * upload
 * Class UploadController
 * @package Dcat\Admin\Extension\Upload\Http\Controllers
 */
class UploadController extends BaseController
{
    use HasFileUpload;

    /**
     * @var string
     * 模块名字
     */
    protected $uploadDirectory='';

    /**
     * UploadController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->initStorage();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpload(Request $request)
    {
        //模块
        $module_name = request('module_name') ? request('module_name') : '';

        //分类
        $category = request('category') ? request('category') : 'image' ;

        //目录
        $directory = request('directory') ? request('directory') : '' ;

        $user_id = request('user_id') ?? 0;

        $where = ['name'=>$category, 'module_name' => $module_name];

        $categoryData  =  $where + ['label' => $category, 'description'=>''];

        //分类
        $now_category = AttachmentCategory::firstOrCreate($where, $categoryData);

        $files = $request->file('upload_image');


        //设定目录
        if(!empty($user_id)){
             $category .= '/' .  $user_id  ;
        }

        if(!empty($directory)){
            $category .= '/' . $directory  ;
        }

        //文件目录模块
        $this->uploadDirectory =  $category;

        //生成保存数据
        $data = [];

        //校验
        //只对
        if ($result = $this->validated($files) AND !$result['status']) {
            return response()->json([
                'status' => false,
                'message' => $result['message']
            ]);
        }

        //执行上传
        foreach ($files as $key => $item) {
            //扩展名
            $extension = $item->getClientOriginalExtension();

            # 临时绝对路径
            $realPath = $item->getRealPath();

            $fileName = generate_random_string() . '.' . $extension;

            $target_directory = $this->formatDir();

            //文件全路径名
            $file_path = $target_directory . $fileName;

            //判断文件是否存在
            $this->storage->putFileAs($target_directory, new File($realPath), $fileName);

            //把扩展名去掉
            $data[$key]['module_name'] = $module_name;
            $data[$key]['name'] = $fileName;
            $data[$key]['path'] = $file_path;
            $data[$key]['user_id'] = $user_id;
            $data[$key]['file_ext'] = $extension;
            $data[$key]['md5'] = md5_file($realPath);
            $data[$key]['url'] = $this->objectUrl($file_path);
        }

        if (count($data) == count($files)) {
            //返回数据
            $response = [
                'state' => 'SUCCESS',
                'imgData' => $data,
                'category_id'  => $now_category->id
            ];

            //上传成功写入数据库
            event(new Uploaded($response));

            //上传成功写入数据库
            return response()->json([
                'status' => true, 'data' => $data
            ]);
        }

        return response()->json([
            'status' => false, 'message' => '上传失败'
        ]);
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $id = request('id');

        try{
            $attachment = Attachment::findOrFail($id);

            if ($this->storage->exists($attachment->url)) {
                $this->storage->delete($attachment->url);
            }

            Attachment::destroy($id);

        }catch (\Exception $exception){

            \Log::info($exception->getMessage() . $exception->getTraceAsString());
        }
    }
}
