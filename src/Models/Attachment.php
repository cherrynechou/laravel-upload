<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/7 0007
 * Time: 13:44
 */

namespace CherryneChou\LaravelUpload\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 * @package Dcat\Admin\Extension\Upload\Models
 */
class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id', 'user_id', 'file_name', 'md5' , 'url', 'file_ext' , 'type'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $prefix = config('upload.database.prefix');
        $this->setTable($prefix . 'attachment');
    }
}
