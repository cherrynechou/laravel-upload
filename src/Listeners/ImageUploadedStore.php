<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/9 0009
 * Time: 14:35
 */

namespace CherryneChou\LaravelUpload\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use CherryneChou\LaravelUpload\Models\Attachment;
use CherryneChou\LaravelUpload\Events\Uploaded;

/**
 * Class ImageUploadedStore
 * @package CherryneChou\LaravelUpload\Models\Listeners
 */
class ImageUploadedStore
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Uploaded  $event
     * @return void
     */
    public function handle(Uploaded $event)
    {

        if($event->resultData['state'] == 'SUCCESS'){
            $imgData = $event->resultData['imgData'];
            $category_id = $event->resultData['category_id'];

            foreach ($imgData as $key=>$item){
                Attachment::create([
                    'file_name'     => $item['name'],                    //文件名称
                    'url'          => $item['path'],                    //本地路径
                    'md5'          => $item['md5'],
                    'user_id'      => $item['user_id'],
                    'file_ext'     => $item['file_ext'],
                    'cat_id'       => $category_id              //分类id
                ]);
            }
        }

    }
}
