<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/11 0011
 * Time: 16:38
 */

namespace CherryneChou\LaravelUpload\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Trait HasFileUpload
 * @package CherryneChou\LaravelUpload\Traits
 */
trait HasFileUpload
{
    /**
     * Storage instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $storage = '';

    /**
     * @throws \Exception
     */
    protected function initStorage()
    {
        $disk  = config('upload.disk');

        $this->disk($disk);
    }

    /**
     * Set disk for storage.
     *
     * @param string $disk Disks defined in `config/filesystems.php`.
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function disk($disk)
    {
        try {
            $this->storage = Storage::disk($disk);
        } catch (\Exception $exception) {
            if (!array_key_exists($disk, config('filesystems.disks'))) {
                upload_error(
                    'Config error.',
                    "Disk [$disk] not configured, please add a disk config in `config/filesystems.php`."
                );

                return $this;
            }

            throw $exception;
        }

        return $this;
    }

    /**
     * Get file visit url.
     *
     * @param $path
     *
     * @return string
     */
    public function objectUrl($path)
    {
        if (URL::isValidUrl($path)) {
            return $path;
        }

        if ($this->storage) {
            if($this->storage->exists($path)){
              return $this->storage->url($path);
            }
        }

        return '';
    }

    /**
     * @param $files
     * @return array
     */
    protected function validated($files)
    {
        if(!is_array($files)){
            return  ['status' => false, 'message' => "不是有效的数组"];
        }

        $size = config('upload.image.size');
        $maxSize = $size . 'M以内';

        foreach ($files as $item) {
            $extension = $item->getClientOriginalExtension();

            if (!$this->maxSize($item)) {
                return ['status' => false, 'message' => "图片过大,限制大小在{$maxSize}"];
            }

            if (!$this->maxNum(count($files))) {
                return ['status' => false, 'message' => '图片上传数量不得超过5张'];
            }

            if (!$this->mines($extension)) {
                return ['status' => false, 'message' => '图片格式不正确'];
            }
        }
        return ['status' => true];
    }

    /**
     * 生成目录
     * @return mixed
     */
    protected function formatDir()
    {

        $directory =  config('upload.image.dir','/{directory}/{Y}/{m}/{d}/');;

        $replacements = [
            '{directory}'   => $this->uploadDirectory,
            '{Y}'           => date('Y'),
            '{m}'           => date('m'),
            '{d}'           => date('d'),
            '{H}'           => date('H'),
            '{i}'           => date('i'),
        ];

        return str_replace(array_keys($replacements), $replacements, $directory);
    }

    /**
     * @param $file
     * @return bool
     */
    protected function maxSize($file)
    {
        $size = $file->getSize() / 1024;

        $config_size = config('upload.image.size',2);

        if ($size > $config_size * 1024) {
            return false;
        }
        return true;
    }

    /**
     * @param $num
     * @return bool
     */
    protected function maxNum($num)
    {
        $config_num =  config('upload.image.num',5);

        if ($num > $config_num) {
            return false;
        }
        return true;
    }

    /**
     * @param $extension
     * @return bool
     */
    protected function mines($extension)
    {
        $config_mines = config('upload.image.mines',[
            'jpg', 'jpeg', 'png', 'bmp', 'gif'
        ]);

        if (in_array($extension, $config_mines)) {
            return true;
        }
        return false;
    }
}
