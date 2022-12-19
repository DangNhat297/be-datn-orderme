<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->integer('discount_percent')->nullable()->after('dish_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->integer('discount_percent')->nullable()->default(0);
        });
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};
