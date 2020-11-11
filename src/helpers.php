<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/8 0008
 * Time: 11:01
 */

use Illuminate\Support\MessageBag;

/**
 * 生成随机字符串
 * 默认 长度为10
 * @param int $length
 * @return string
 */
if(!function_exists('generate_random_string')){
    function generate_random_string($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('upload_info')){

    function upload_info($title, $message = '', $type = 'info')
    {
        $message = new MessageBag(get_defined_vars());

        session()->flash($type, $message);
    }
}

if(!function_exists('upload_error')){
    /**
     * Flash a error message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function upload_error($title, $message = '')
    {
        upload_info($title, $message, 'error');
    }
}


