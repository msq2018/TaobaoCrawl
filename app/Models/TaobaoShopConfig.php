<?php

namespace App\Models;



class TaobaoShopConfig extends BaseModel
{
    
    const PLATFORM_TAOBAO = "taobao";
    
    const PLATFORM_TMALL = "tmall";

    const PLATFORM_1688 = "1688";

    /**
     * 已添加状态
     */
    const  APPENDED_STATUS = 2;
    /**
     * 关闭状态
     */
    const INITIALIZE_STATUS = 1;


    public function getPlatformLabel($status){
        switch ($status){
            case self::PLATFORM_TAOBAO:
                return "淘宝";
            case self::PLATFORM_TMALL:
                return "天猫";
            case self::PLATFORM_1688 :
                return "1688";
        }
        return $status;
    }
    public function getStatusLabel($status){
        if ($status == self::APPENDED_STATUS){
            return "已添加";
        }elseif ($status == self::INITIALIZE_STATUS){
            return "未添加";
        }
        return null;
    }
    
}
