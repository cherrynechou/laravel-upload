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
        $category = request('category') ?? 'image';

        $user_id = request('user_id') ?? 0;

        $where = ['name'=>$category, 'user_id'=>$user_id];

        $categoryData  =  $where + ['label' => $category, 'description'=>''];
        //分类
        $now_category = AttachmentCategory::firstOrCreate($where, $categoryData);

        $files = $request->file('upload_image');

        $directory = request('directory') ? request('directory') : 'images' ;

        if(!empty($user_id)){
            $directory=$directory .'/' . $user_id;
        }


        //文件目录模块
        $this->uploadDirectory = $directory;

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
            $realPath = $item->path();

            $fileName = generate_random_string() . '.' . $extension;

            $target_directory = $this->formatDir();

            //文件全路径名
            $file_path = $target_directory . $fileName;

            //判断文件是否存在
            $this->storage->putFileAs($directory, new File($realPath), $fileName);

            //把扩展名去掉
            $data[$key]['name'] = $fileName;
            $data[$key]['path'] = $file_path;
            $data[$key]['user_id'] = $user_id;
            $data[$key]['file_ext'] = $extension;
            $data[$key]['md5'] = md5($file_path);
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
        $id = $request->input('id');

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