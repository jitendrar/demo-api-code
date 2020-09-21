<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsImagesTable extends Migration
{
    public function up()
    {
        Schema::create('products_images', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('src',255);
            $table->tinyinteger('is_primary')->default(0);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('products_images');
    }
}
