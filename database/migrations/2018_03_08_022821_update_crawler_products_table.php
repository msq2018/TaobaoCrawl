<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCrawlerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("crawler_products",function (Blueprint $table){
            $table->renameColumn('product_link', 'link');
            $table->renameColumn('attributes', 'specifications');
            $table->string("__id");
            $table->string("__time");
            $table->string("__url");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
