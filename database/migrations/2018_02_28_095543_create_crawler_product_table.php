<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrawlerProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id");
            $table->string("name");
            $table->string("product_link");
            $table->string("origin_price");
            $table->text("gallery_images");
            $table->text("params");
            $table->text("attributes");
            $table->text("detail");
            $table->string("shop_id");
            $table->string("shop_name");
            $table->string("shop_link");
            $table->string("platform");
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
        Schema::dropIfExists('crawler_products');
    }
}
