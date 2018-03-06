<?php

namespace App\Models\Catalog;

use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $table = "catalog_products";


    public function storeProductFormCrawler($data)
    {
    	$crawlerProductId = $data['id']; $newData = [];
    	foreach ($data as $key => $value) {
    		if ($key == 'origin_price') {
    			$newData['price'] = $value;
    		}elseif ($key == "params") {
    			$newData['custom_option'] = $value;
    		}elseif ($key == "detail") {
    			$newData['description'] = $value;
    		}else{
    			if (strpos("_", $key) === false) {
    				$newData[$key] = $value;
    			}
    		}
    	}
    	print_r($newData);exit();
    }

}
