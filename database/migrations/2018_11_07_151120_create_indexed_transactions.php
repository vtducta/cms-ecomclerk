<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexedTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indexed_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('product_sales',20,2)->nullable();
            $table->decimal('sales_taxes',20,2)->nullable();
            $table->decimal('marketplace_taxes',20,2)->nullable();
            $table->decimal('selling_fees',20,2)->nullable();
            $table->decimal('fba_fees',20,2)->nullable();
            $table->decimal('totals',20,2)->nullable();
            $table->decimal('costs',20,2)->nullable();
            $table->dateTime('date_time')->nullable();
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
        Schema::drop('indexed_transactions');
    }
}
