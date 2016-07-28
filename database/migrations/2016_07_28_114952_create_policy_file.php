<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('consistency_checks')->truncate();
        Schema::create('policy_files', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('filename');
        });

        Schema::table('consistency_checks', function(Blueprint $table){
            $table->integer('policy_file_id')->unsigned();
            $table->foreign('policy_file_id')->references('id')->on('policy_files')->onDelete('cascade');
            $table->dropColumn('policy');
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
            $table->dropForeign('consistency_checks_policy_file_id_foreign');
            $table->dropColumn('policy_file_id');
            $table->mediumText('policy');
        });
        Schema::drop('policy_files');
    }
}
