<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('upload.database.prefix');

        $table_name = $prefix . 'attachment_category';

        if (!Schema::hasTable( $table_name )) {
            Schema::create( $table_name, function (Blueprint $table) {

                $table->bigIncrements('id');

                $table->integer('parent_id')->nullable()->default(0);
                $table->string('module_name',100)->comment('模块名称');
                $table->string('name'); //分类的名字
                $table->string('label'); //分类的名字

                $table->text('description')->nullable(); //分类描述
                $table->integer('sort')->default(99); //分类排序

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

        $table_name = $prefix . 'attachment_category';

        Schema::dropIfExists($table_name);
    }
}
