<?php

namespace App\Models\Crawler;

use App\Models\BaseModel;

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


}
