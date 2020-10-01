<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryMasterTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->bigInteger('phone');
            $table->text('picture')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->timestamps();
        });
    }

   public function down()
    {
        Schema::dropIfExists('delivery_master');
    }
}
