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
    $router->resource("crawler","Crawler\\ManageController");

    $router->post("crawler/app_switch","Crawler\\ManageController@appSwitch")
        ->name("crawler.app.switch");
    $router->post("crawler/setUrl","Crawler\\ManageController@setScanUrl")
        ->name("crawler.set_url");

    $router->get('example','ExampleController@index');

    $router->get('example/start','ExampleController@start');

    $router->get('example/stop','ExampleController@stop');


});
