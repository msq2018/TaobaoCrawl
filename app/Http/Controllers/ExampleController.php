<?php

namespace App\Http\Controllers;





use Goutte\Client;
use Shenjian\ShenjianClient;

class ExampleController extends Controller
{

    public function index()
    {
        $user_key = "9909134dbe-M2IxNjA5MW";
        $user_secret = "Y5OTA5MTM0ZGJlMz-326f4e354b3b160";
        $params['app_name'] = "test";
        $params['app_info'] = "test crawler";
        $params['code'] = base64_encode("alert(1)");
        try{
            $shenjian_client = new ShenjianClient($user_key, $user_secret);
            $crawler = $shenjian_client->createCrawler($params);
        }catch (ShenjianException $e){
            printf($e->getMessage() . "\n");
            return;
        }
        print("Crawler AppId: " . $crawler->getAppId() . "\n");
        print("Crawler Name: " . $crawler->getName() . "\n");
        print("Crawler Status: " . $crawler->getStatus() . "\n");
        print("Crawler TimeCreate: " . $crawler->getTimeCreate());
    }

   
}
