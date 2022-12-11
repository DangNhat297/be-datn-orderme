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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('price_sale')->default(0)->after('total');
            $table->integer('price_none_sale')->after('price_sale')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable()->after('price_sale'); 
            $table->string('note')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['price_sale', 'price_none_sale', 'coupon_id']);
            $table->string('note')->change();
        });
    }
};
