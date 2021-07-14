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
 * Class AttachmentCategory
 * @package CherryneChou\LaravelUpload\Models
 */
class AttachmentCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id','module_name','name','label','description','sort'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $prefix = config('upload.database.prefix');
        $this->setTable($prefix . 'attachment_category');
    }
}
