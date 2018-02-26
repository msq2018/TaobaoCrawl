<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->get('example','ExampleController@index');
    //shop config
    $router->resource("shop_config","Shop\\ConfigController");

    $router->get('example/start','ExampleController@start');

    $router->get('example/stop','ExampleController@stop');


});
