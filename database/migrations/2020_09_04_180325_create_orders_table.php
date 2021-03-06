<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('address_id')->nullable();
            $table->float('total_price', 11)->nullable();
            $table->double('delivery_charge', 11, 2)->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('delivery_time', 100)->nullable();
            $table->text('special_information')->nullable();
            $table->string('order_number', 20)->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->time('actual_delivery_time')->nullable();
            $table->string('order_status', 50)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
