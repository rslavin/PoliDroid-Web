<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApkFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apk_files', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('filename');
            $table->string('mime');
            $table->string('original_filename');
        });
        
        Schema::table('consistency_checks', function(Blueprint $table){
            $table->integer('apk_file_id')->unsigned();
            $table->foreign('apk_file_id')->references('id')->on('apk_files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consistency_checks', function(Blueprint $table){
            $table->dropForeign('consistency_checks_apk_file_id_foreign');
            $table->dropColumn('apk_file_id');
        });
        Schema::drop('apk_files');
    }
}
