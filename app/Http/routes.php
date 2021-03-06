<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');


    //shop config
    $router->resource("shop_config","Shop\\ConfigController");


    //crawler
    $router->get("crawler","Crawler\\ManageController@index");

    $router->get('crawler/get_status',"Crawler\\ManageController@getStatus")
        ->name("crawler.get_status");
    $router->post("crawler/app_switch","Crawler\\ManageController@appSwitch")
        ->name("crawler.app.switch");
    $router->post("crawler/setUrl","Crawler\\ManageController@setScanUrl")
        ->name("crawler.set_url");
    $router->post("get_crawler_result","Crawler\\ManageController@getCrawlerResult")
        ->name("crawler.get_crawler_result");

    //crawler product
    $router->get("crawler_product","Crawler\\ProductController@index");
    $router->get("crawler_getProduct","Crawler\\ProductController@getDataSource")
        ->name("crawler.get_product");



    //catalog
    $router->resource("catalog/product","Catalog\\ProductController");

    $router->match(['get','head'],"catalog/product/publish/{product}","Catalog\\ProductController@publishCrawlerProduct")
        ->name("catalog.publish.product");

    $router->match(['put','patch'],"catalog/product/publish/{product}","Catalog\\ProductController@storeFormCrawler")
        ->name("store.product.form.crawler");



    $router->get('example','ExampleController@index');

    $router->get('example/start','ExampleController@start');

    $router->get('example/stop','ExampleController@stop');


});
