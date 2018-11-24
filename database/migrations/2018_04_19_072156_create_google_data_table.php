<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('accountId', 20)->nullable();
            $table->string('viewId', 20)->nullable();
            $table->decimal('impressions')->nullable();
            $table->decimal('clicks')->nullable();
            $table->decimal('cost')->nullable();
            $table->decimal('cost_per_conversion')->nullable();
            $table->decimal('cpm')->nullable();
            $table->decimal('cpc')->nullable();
            $table->decimal('ctr')->nullable();
            $table->decimal('cost_per_transaction')->nullable();
            $table->decimal('cost_per_goal_conversion')->nullable();
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
        Schema::dropIfExists('google_data');
    }
}
