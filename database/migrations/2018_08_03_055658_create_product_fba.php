<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFba extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_fba', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('amazon_category')->nullable();
            $table->string('brand')->nullable();
            $table->string('buy_box')->nullable();
            $table->string('asin')->nullable();
            $table->string('upc')->nullable();
            $table->decimal('pack_unit')->nullable();
            $table->decimal('cost')->nullable();
            $table->decimal('buy_box_price')->nullable();
            $table->string('buy_box_share')->nullable();
            $table->decimal('fba_fee')->nullable();
            $table->decimal('profit')->nullable();
            $table->decimal('estimated_monthly_sales')->nullable();
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
        Schema::dropIfExists('product_fba');        
    }
}
