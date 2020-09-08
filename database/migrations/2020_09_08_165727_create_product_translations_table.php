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
            // mandatory fields
           $table->bigIncrements('id'); // Laravel 5.8+ use bigIncrements() instead of increments()
           $table->string('locale')->index();
           $table->unsignedInteger('product_id');

           // Foreign key to the main model
           $table->unique(['product_id', 'locale']);
           //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

           // Actual fields you want to translate
           $table->string('product_name');
           $table->text('description');
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
