<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeightProductFbaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_fba', function (Blueprint $table) {
            $table->decimal('package_length')->nullable();
            $table->decimal('package_height')->nullable();
            $table->decimal('package_width')->nullable();
            $table->decimal('package_weight')->nullable();
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
            $table->dropColumn('package_length');
            $table->dropColumn('package_height');
            $table->dropColumn('package_width');
            $table->dropColumn('package_weight');
        });
    }
}
