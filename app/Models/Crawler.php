<?php

namespace App\Models;

use App\Models\Crawler\Product as CrawlerProduct;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
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
        TaobaoShopConfig::PLATFORM_TAOBAO=>916016
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

    public function getUserSecret(){
        return $this->userSecret;
    }

    public function appRestart($appId){
        $status = $this->getCrawlerStatus($appId);
        if ($status == "stopped"){
            $this->appSwitch($appId,"start");
        }else{
            $this->appSwitch($appId,"stop");
            $this->appSwitch($appId,"start");
        }
        return;
    }

    public function appSwitch($appId,$status){
        if (in_array($status,['start','resume','pause','stop'])){
            $function = "{$status}Crawler";
            if ($changedStatus=$this->client->$function($appId)){
                $this->validateCrawlerStatus($appId,['stopped','running','paused']);
                return true;
            }
        }
        return false;
    }
    private function validateCrawlerStatus($appId,array $allowStatuses){
        $status = $this->getCrawlerStatus($appId);
        if (!in_array($status,$allowStatuses)){
            sleep(2);
            $this->validateCrawlerStatus($appId,$allowStatuses);
        }
        return $status;
    }
    /**
     * @author Ma ShaoQing <mashaoqing@jeulia.net>
     */
    public function getCrawlerStatus($appId){
      return $this->client->getCrawlerStatus($appId);
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
            if ($this->postCustomConfig($this->appIds[$type],$params) === 0){
                return true;
            }
        }
        return false;
    }

    public function postCustomConfig($appId,$params)
    {
        sleep(1);
        $time = time();
        $sign = md5($this->userKey.$time.$this->userSecret);
        $url = "http://www.shenjianshou.cn/rest/crawler/config?user_key={$this->userKey}&timestamp={$time}&sign={$sign}&crawler_id={$appId}";
        $client = new HttpClient();
        $response  = $client->post($url,["form_params"=>$params]);
        $result = json_decode($response->getBody(),true);
        return $result['error_code'];
    }

    public function getAppListData()
    {
        $appList = $this->client->getAppList();
        $data = [];
        foreach ($appList as $key=>$app){
            if (in_array($app->getAppId(),$this->appIds)){
                $data[$key]['app_id'] = $app->getAppId();
                $data[$key]['info'] = $app->getInfo();
                $data[$key]["name"] = $app->getName();
                $data[$key]["status"] = $this->getCrawlerStatus($app->getAppId());
            }
        }
        return $data;
    }

    public function getAppIdWithType($type){
        if (isset($this->appIds[$type])){
            return $this->appIds[$type];
        }
        return  false;
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

    public function getGraphQLResult($appId)
    {
        
        $source = $this->client->getCrawlerSource($appId);
        $sourceId =  $source->getAppId();
        //http://graphql.shenjian.io/?user_key=用户key&timestamp=秒级时间戳&sign=签名&source_id=数据源ID&query=查询请求
    
        $cursor = 0;
        $has_next_page = false;
        $try = 0;
        do {
            $time = time();
            $sign = md5($this->userKey.$time.$this->userSecret);
            
            $query = urlencode("source(__id:{gt:$cursor},limit:20,sort:\"asc\"){data{},page_info{end_cursor,has_next_page}}");
            $url = "http://graphql.shenjian.io/?user_key={$this->userKey}&timestamp={$time}&sign={$sign}&source_id={$sourceId}&query={$query}";
            // 2. 发送请求并解析结果
            $client = new HttpClient();
            $response  = $client->get($url);
            $result = json_decode($response->getBody(), true);
            if ($result && $result['code'] == 0) {
                file_put_contents("test.php", "{$cursor}\n",FILE_APPEND);
                $try = 0;
                $page_info = $result['result']['page_info'];
                // 更新cursor, 下次从新的cursor开始查
                $cursor = $page_info['end_cursor'];
                $has_next_page = $page_info['has_next_page'];
                $items = $result['result']['data'];
                foreach ($items as $item) {
                    $item['platform'] = array_search($appId,$this->appIds);
                    CrawlerProduct::getModel()->saveResultFormGraphQL($item);
                }
            } else {// graphql请求失败, 重试
                $try++;
                // 重试3次还是失败, 退出前记录cursor, 以便下次继续
                if ($try > 3) {
                    Log::notice("crawler get result  try too many times, cursor: {$cursor} ,result:".$result['error_info']);
                    break;
                }
            }
            if (!$has_next_page) {// 遍历完了
                Log::info("crawler get result no more data");
                break;
            }
            sleep(6);
        } while(true);
        return true;
    }
}
