<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->foreign('product_id', 'product_translations_ibfk_1')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropForeign('product_translations_ibfk_1');
        });
    }
}
