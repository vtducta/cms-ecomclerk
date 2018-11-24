<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('user_type_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->tinyInteger('status');
            $table->string('created_by');
            $table->string('updated_by');
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('SET NULL');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
