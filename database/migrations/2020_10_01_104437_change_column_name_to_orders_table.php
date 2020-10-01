<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNameToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('assign_delivery_boy_id');
            $table->integer('delivery_master_id')->nullable()->after('payment_method');

        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('assign_delivery_boy_id')->nullable()->after('payment_method');
            $table->dropColumn('delivery_master_id');
        });
    }
}
