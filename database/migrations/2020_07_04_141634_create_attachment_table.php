<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAttachmentTable
 */
class CreateAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('upload.database.prefix');

        $table_name = $prefix . 'attachment';

        if (!Schema::hasTable( $table_name )) {
            Schema::create( $table_name, function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->integer('cat_id')->default(0)->comment('文件分组'); //分类ID
                $table->integer('user_id')->nullable()->default(0)->comment('用户id'); //分类ID
                $table->string('module_name',100)->comment('模块名称');
                $table->string('file_name',255)->comment('文件名称');
                $table->string('md5',32)->comment('图片md5');
                $table->string('url',255)->comment('本地url');    //本地url
                $table->string('file_ext')->comment("文件后缀");
                $table->tinyInteger('type')->default(1)->comment('资源类型');
                $table->index(['user_id','md5']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('upload.database.prefix');

        $table_name = $prefix . 'attachment';

        Schema::dropIfExists($table_name);
    }
}
