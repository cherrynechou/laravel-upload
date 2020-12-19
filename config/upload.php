<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 17:03
 */
return [
    'database'=>[
        'connection'                    =>      '',
        //Database database config name
        'prefix'                        =>      env('DB_PREFIX',  ''),    //项目表前缀
    ],
    'disk'                              =>      env('EASYCMS_DISK',  'public'),   //存诸,
    /*
    |--------------------------------------------------------------------------
    | 上传设置
    | model 文件目录
    |--------------------------------------------------------------------------
    */
    //上传图片配置
    'image'=>[
        'dir'   => '/{directory}/{Y}/{m}/{d}/',
        'size'  => 2,    //文件大小  单位M
        'num'   => 5,     //文件个数
        'mimes' => ['jpg','jpeg', 'png', 'bmp', 'gif', 'mp4'],
    ],
    //上传视频配置
    'video'=>[
        'dir'   => '/{directory}/{Y}/{m}/{d}/',
        'size'  => 2,    //文件大小  单位M
        'num'   => 5,     //文件个数
        'mimes' => ['mp4'],
    ]

];
