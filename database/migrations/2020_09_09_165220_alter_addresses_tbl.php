<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressesTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('address_line_1', 200)->nullable()->after('user_id');
            $table->string('address_line_2', 200)->nullable()->after('address_line_1');
            $table->string('city', 100)->nullable()->after('address_line_2');
            $table->string('zipcode', 100)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
    }
}
