<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopifyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('accountId', 20)->nullable();
            $table->string('viewId', 20)->nullable();
            $table->string('property_name')->nullable();
            $table->string('unique_purchases')->nullable();
            $table->string('transaction_revenue')->nullable();
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
        Schema::dropIfExists('shopify_data');
    }
}
