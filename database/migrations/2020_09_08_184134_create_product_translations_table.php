<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id');
            $table->string('locale')->index();
            $table->string('product_name');
            $table->text('description');
            $table->integer('units_in_stock')->nullable();
            $table->string('units_stock_type', 20)->nullable();
            $table->double('unity_price', 11, 2)->nullable();
            $table->unique(['product_id', 'locale'], 'product_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
}
