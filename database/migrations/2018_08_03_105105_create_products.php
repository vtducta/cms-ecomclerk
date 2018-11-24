<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('cost')->nullable();
            $table->string('amazon_title')->nullable();
            $table->string('brand')->nullable();
            $table->string('asin')->nullable();
            $table->string('buybox_win')->nullable();
            $table->string('number_of_sellers')->nullable();
            $table->string('weight')->nullable();
            $table->string('sales_rank')->nullable();
            $table->string('sales_rank_30')->nullable();
            $table->string('sales_rank_90')->nullable();
            $table->string('reviews')->nullable();
            $table->string('ratings')->nullable();
            $table->string('sales')->nullable();
            $table->string('amazon_upc_ean')->nullable();
            $table->string('upc_ean')->nullable();
            $table->string('amazon_buy_box_price')->nullable();
            $table->string('net_after_fba')->nullable();
            $table->string('pack_cost')->nullable();
            $table->integer('number_of_packs')->nullable();
            $table->decimal('gross_profit_fba')->nullable();
            $table->decimal('gross_roi')->nullable();
            $table->string('is_eligible_for_prime')->nullable();
            $table->decimal('profit')->nullable();
            $table->string('po_status')->nullable();
            $table->text('reason')->nullable();
            $table->string('number_of_prime_sellers')->nullable();
            $table->string('quantity_buy_in')->nullable();
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
        Schema::dropIfExists('products');
    }
}
