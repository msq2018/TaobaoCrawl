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
        $app_id = 916016;
        $params['scanUrls'] = array("shop122157493.taobao.com");
        $time = time();
        $sign = md5($user_key.$time.$user_secret);
        //$url = "http://www.shenjianshou.cn/rest/crawler/config?user_key={$user_key}&timestamp={$time}&sign={$sign}&crawler_id={$app_id}";
       // $url ="http://www.shenjianshou.cn/rest/crawler/start?user_key={$user_key}&timestamp={$time}&sign={$sign}&crawler_id={$app_id}";
        $url = "http://www.shenjianshou.cn/rest/crawler/status?user_key={$user_key}&timestamp={$time}&sign={$sign}&crawler_id={$app_id}";

        var_dump($url);exit();


        try{
            $shenjian_client = new ShenjianClient($user_key, $user_secret);
            //$status = $shenjian_client->stopCrawler($app_id);
            $shenjian_client->configCrawlerCustom($app_id, $params);
            //$status = $shenjian_client->startCrawler($app_id);
        }catch (ShenjianException $e){
            printf($e->getMessage() . "\n");
            return;
        }

        print("ConfigCrawlerCustom: OK");

        exit();
    }

    public function stop(){
        $user_key = "9909134dbe-M2IxNjA5MW";
        $user_secret = "Y5OTA5MTM0ZGJlMz-326f4e354b3b160";
        $app_id = 916016;
        $shenjian_client = new ShenjianClient($user_key, $user_secret);
        $status = $shenjian_client->stopCrawler($app_id);
        print_r($status);exit();
    }

    public function start(){
        $user_key = "9909134dbe-M2IxNjA5MW";
        $user_secret = "Y5OTA5MTM0ZGJlMz-326f4e354b3b160";
        $app_id = 916016;
        $shenjian_client = new ShenjianClient($user_key, $user_secret);
        $status = $shenjian_client->startCrawler($app_id);
        print_r($status);exit();
    }

}
