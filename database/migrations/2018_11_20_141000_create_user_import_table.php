<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_import', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->bigInteger('job_id');
            $table->string('file_name',500);
            $table->unsignedInteger('row_count')->default(0);
            $table->string('result_file',500);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_import');
    }
}
