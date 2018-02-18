<?php

namespace App\Http\Controllers;




class ExampleController extends Controller
{

    public function index()
    {

        $crawler = \Goutte::request('GET', 'http://baidu.com');
        $crawler->filter('.title')->each(function ($node) {
        dump($node->text());
    });

    //return view('welcome');
        
    }

   
}
