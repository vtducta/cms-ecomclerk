<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailchimpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailchimp_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('campaign_id')->nullable();
            $table->string('campaign_title')->nullable();
            $table->decimal('clicks')->nullable();
            $table->decimal('unique_clicks')->nullable();
            $table->decimal('unique_subscriber_clicks')->nullable();
            $table->decimal('orders')->nullable();
            $table->decimal('spent')->nullable();
            $table->decimal('revenue')->nullable();
            $table->string('click_rate', 30)->nullable();
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
        Schema::dropIfExists('mailchimp_data');
    }
}
