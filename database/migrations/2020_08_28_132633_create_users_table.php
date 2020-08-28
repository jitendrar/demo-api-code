<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->bigInteger('phone')->unique('users_email_unique');
            $table->bigInteger('new_phone')->nullable();
            $table->string('phone_otp', 15)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->string('password');
            $table->string('languague')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
