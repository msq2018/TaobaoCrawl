<?php

namespace App\Models\Catalog;

use App\Models\BaseModel;
use App\Models\Crawler\Product as CrawlerProdcut;
use Illuminate\Support\Facades\Storage;

class Product extends BaseModel
{
    protected $table = "catalog_products";

    protected function setCategoryIdAttribute($categoryId){
        if (is_array($categoryId)){
            $this->attributes['category_id'] = serialize($categoryId);
        }
    }

    protected function setImagesAttribute($images){
        if (is_array($images)){
            $this->attributes['images'] = serialize($images);
        }
    }

    protected function setDescriptionAttribute($description){
        if ($description){
            $this->attributes['description'] = htmlspecialchars($description);
        }
    }

    protected function setStatusAttribute($status){
        if ($status =='on'){
            $status = 1;
        }else{
            $status = 0;
        }
        $this->attributes['status'] = $status;
    }

    protected function getImagesAttribute($images){
       return unserialize($images);
    }


    public function saveFormCrawlerProduct($data){
        $data = $this->getDataFormCrawlerProduct($data);
        foreach ($data as $key=>$item){
            $this->$key = $item;
        }
       return $this->save();
    }

    protected function getDataFormCrawlerProduct($data)
    {
        $crawlerProductId = $data['id']; $newData = [];
        $crawlerProductModel =  CrawlerProdcut::find($crawlerProductId);
        $images = $crawlerProductModel->gallery_images;
        if ($uploadImages = $this->uploadImages()){
            $images = array_merge($images,$uploadImages);
        }
        $newData['images'] = $images;
        foreach ($data as $key => $value) {
            if ($key == 'origin_price') {
                $newData['price'] = $value;
            }
            if ($key == "params") {
                $newData['custom_options'] = serialize($value);
            }
            if ($key == "detail") {
                $newData['description'] = $value;
            }
            $newData[$key] = $value;
        }
        foreach ($newData as $key=>$value){
            if (strpos($key,"_") === 0 || in_array($key,['id','gallery_images','params','origin_price','detail'])) {
                unset($newData[$key]);
            }
        }
        return $newData;
    }

    private function uploadImages()
    {
        $files = request()->file();
        if (isset($files['gallery_images']) && $images = $files['gallery_images']){
            $filePath = [];
            foreach ($images as $image){
                $filePath[] = Storage::disk('admin')->url($image->store('product',"admin"));
            }
            return $filePath;
        }
        return [];
    }


}
