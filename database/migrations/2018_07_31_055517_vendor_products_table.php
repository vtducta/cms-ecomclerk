<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->string('product_title')->nullable();
            $table->integer('vendor_item_number')->nullable();
            $table->string('upc')->nullable();
            $table->decimal('vendor_cost')->nullable();
            $table->integer('case_quantity')->nullable();
            $table->decimal('weight')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_products');
    }
}
