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
            $table->string("category_id")->nullable();
            $table->string("name");
            $table->string("sku");
            $table->string("qty");
            $table->string("status");
            $table->decimal("price");
            $table->decimal("special_price")->nullable();
            $table->text("images")->nullable();
            $table->text("short_description");
            $table->text("description")->nullable();
            $table->text("custom_options")->nullable();
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
