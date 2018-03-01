<?php
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/3/1
 * Time: 9:35
 */

namespace App\Extensions;

use Encore\Admin\Admin;

class ImportDataSourceButton
{
    protected $sourceType ;

    public function __construct($type)
    {
        $this->sourceType = $type;
    }

    public function script(){
        $url = route("crawler.get_product");
        return <<<EOF
        $(".update-data-socurce").click(function(){
            $.get("{$url}",{"type":"{$this->sourceType}"},function(response){
                
            })    
        });
    
EOF;

    }

    public function render(){
        Admin::script($this->script());
        return "<a href='#' data-type='{$this->sourceType}' class='btn btn-sm btn-success update-data-socurce'>更新所有淘宝数据</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}