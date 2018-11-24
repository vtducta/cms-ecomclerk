<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToProductFbaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_fba', function (Blueprint $table) {

            $table->boolean('qty_available')->nullable();
            $table->timestamp('restock_date')->nullable();
            $table->integer('restock_qty')->nullable();
            $table->decimal('sale_7day')->nullable();
            $table->decimal('sale_15day')->nullable();
            $table->decimal('sale_30day')->nullable();
            $table->decimal('sale_45day')->nullable();
            $table->decimal('sale_60day')->nullable();
            $table->integer('inbound_qty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_fba', function (Blueprint $table) {
            $table->dropColumn('qty_available');
            $table->dropColumn('restock_date');
            $table->dropColumn('restock_qty');
            $table->dropColumn('sale_7day');
            $table->dropColumn('sale_15day');
            $table->dropColumn('sale_30day');
            $table->dropColumn('sale_45day');
            $table->dropColumn('sale_60day');
            $table->dropColumn('inbound_qty');
        });
    }
}
