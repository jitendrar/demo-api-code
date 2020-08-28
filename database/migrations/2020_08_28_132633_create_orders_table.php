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
            $table->integer('user_id')->nullable();
            $table->integer('address_id')->nullable();
            $table->float('delivery_charge', 11, 5)->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('special_information')->nullable();
            $table->integer('order_number')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->string('order_status', 50)->nullable();
            $table->dateTime('actual_delivery_time')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
