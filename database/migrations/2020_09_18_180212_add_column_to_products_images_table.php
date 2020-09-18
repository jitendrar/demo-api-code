<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductsImagesTable extends Migration
{
    public function up()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('src');
        });
    }

    public function down()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
}
