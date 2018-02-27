<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Request;
use Shenjian\ShenjianClient;
use GuzzleHttp\Client as HttpClient;

class Crawler extends BaseModel
{

    protected $primaryKey = "app_id";

    /**
     * 神箭手 user key
     * @var string
     */
    protected $userKey = "9909134dbe-M2IxNjA5MW";
    /**
     * 神箭手 user secret
     * @var string
     */
    protected $userSecret = "Y5OTA5MTM0ZGJlMz-326f4e354b3b160";
    /**
     * 神箭手 app ids
     * @var array
     */
    protected $appIds = [
        "taobao"=>916016
    ];

    protected $client = null;

    protected $httpClient = null;

    public function _construct()
    {
        if (is_null($this->client)){
            $this->client = new ShenjianClient($this->userKey, $this->userSecret);
        }
        return parent::_construct();
    }

    /**
     * @author Ma ShaoQing <mashaoqing@jeulia.net>
     */
    public function getAppStatus($appId){
       $data = $this->getAppListData();
        foreach ($data as $item){
            if ($item['app_id'] == $appId){
                return $item['status'];
            }
        }
        return false;
    }

    public  function addScanUrlToCrawler($type,$link){
        if (isset($this->appIds[$type])){
            $configCustomList = $this->client->configCrawlerCustomGet($this->appIds[$type]);
            $cvalue = [];
            foreach ($configCustomList as $item){
                if ($item->getKey() == "scanUrls"){
                    $cvalueString = stripslashes($item->getCvalue());
                    eval('$cvalue = '.$cvalueString.';');
                }
            }
            array_push($cvalue,$link);
            $params['scanUrls'] = $cvalue;
            if ($this->postCustomConfig($this->appIds[$type],$params) == 200){
                return true;
            }
        }
        return false;
    }

    public function postCustomConfig($appId,$params)
    {
        $time = time();
        $sign = md5($this->userKey.$time.$this->userSecret);
        $url = "http://www.shenjianshou.cn/rest/crawler/config?user_key={$this->userKey}&timestamp={$time}&sign={$sign}&crawler_id={$appId}";
        $client = new HttpClient();
        $response  = $client->post($url,["form_params"=>$params]);
        return $response->getStatusCode();
    }


    public function paginate()
    {
        $request = new Request();
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $start = ($page-1)*$perPage;
        $data = static::hydrate($this->getAppListData());
        $paginator = new LengthAwarePaginator($data, count($data), $perPage);
        $paginator->setPath(url()->current());
        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }

    private function getAppListData()
    {
        $appList = $this->client->getAppList();
        $data = [];
        foreach ($appList as $key=>$app){
            if (in_array($app->getAppId(),$this->appIds)){
                $data[$key]['app_id'] = $app->getAppId();
                $data[$key]['info'] = $app->getInfo();
                $data[$key]["name"] = $app->getName();
                $data[$key]["status"] = $app->getStatus();
            }
        }
        return $data;
    }
}