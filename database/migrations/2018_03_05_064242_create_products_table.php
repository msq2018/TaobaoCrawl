<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("category_id");
            $table->string("name");
            $table->string("sku");
            $table->string("qty");
            $table->string("status");
            $table->decimal("price");
            $table->decimal("special_price");
            $table->text("images");
            $table->text("short_description");
            $table->text("description");
            $table->text("custom_options");
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
        Schema::dropIfExists('catalog_products');
    }
}
