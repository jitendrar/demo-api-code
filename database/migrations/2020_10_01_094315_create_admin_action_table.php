<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminActionTable extends Migration
{
    public function up()
    {
        Schema::create('admin_action', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_action');
    }
}
