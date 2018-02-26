<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaobaoShopConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taobao_shop_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->text("shop_name");
            $table->text("shop_link");
            $table->tinyInteger("status")->default(1);
            $table->char("platform");
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
        Schema::dropIfExists('taobao_shop_configs');
    }
}
