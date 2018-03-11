<?php

namespace App\Models\Crawler;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Schema;
use League\Flysystem\Exception;

class Product extends BaseModel
{
    protected $table = "crawler_products";

    /*protected $fillable = ["product_id","name","product_link","origin_price","gallery_images","params","attributes","detail","shop_id","shop_name","shop_link","platform"];*/

    public function setGalleryImagesAttribute($pictures){
        if (is_array($pictures)){
            $this->attributes['gallery_images'] = serialize($pictures);
        }
    }

    public function getGalleryImagesAttribute($pictures)
    {
        return unserialize($pictures);
    }

    public function setSpecificationsAttribute($specifications){
        if (is_array($specifications)){
            $this->attributes['specifications'] = serialize($specifications);
        }
    }

    public function setParamsAttribute($params){
        if (is_array($params)){
            $this->attributes['params'] = serialize($params);
        }
    }

    public function setDetailAttribute($detail){
        if (!empty($detail)){
            $this->attributes['detail'] = htmlspecialchars($detail);
        }
    }

    public function getParamsAttribute($params){
        return unserialize($params);
    }

    public function getDetailAttribute($detail){
        return htmlspecialchars_decode($detail);
    }


    public function saveResultFormGraphQL(array $data){
        try{
            $fields = Schema::getColumnListing($this->getTable());
            $model = $this->where('product_id',$data['product_id'])->first()?:$this;
            foreach ($data as $key=>$value){
                if (in_array($key,$fields)){
                    $model->$key = $value;
                }
            }
            return $model->save();
        }catch (Exception $e){
            report($e);
        }
        return false;
    }





}
