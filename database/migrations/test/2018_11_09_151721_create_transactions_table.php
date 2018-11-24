<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('transactions', function (Blueprint $table) {
			 
            $table->bigIncrements('id');
			$table->dateTime('date_time');
			$table->bigInteger('settlement_id')->unsigned();
			$table->string('type')->nullable();
			$table->string('order_id');
			$table->string('sku');
			$table->mediumText('description')->nullable();
			$table->decimal('quantity',20,2);
			$table->string('marketplace')->nullable();
			$table->string('fulfillment')->nullable();
			$table->string('order_city')->nullable();
			$table->string('order_state')->nullable();
			$table->string('order_postal')->nullable();
			$table->decimal('product_sales',20,2);
			$table->decimal('shipping_credits',20,2);
			$table->decimal('gift_wrap_credits',20,2);
			$table->decimal('promotional_rebates',20,2);
			$table->decimal('sales_tax_collected',20,2);
			$table->decimal('Marketplace_Facilitator_Tax',20,2);
			$table->decimal('selling_fees',20,2);
			$table->decimal('fba_fees',20,2);
			$table->decimal('other_transaction_fees',20,2);
			$table->decimal('other',20,2)->nullable();
			$table->decimal('total',20,2);
			$table->decimal('cost',20,2);   

            $table->timestamps();
			$table->unique('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('transactions');
    }
}
