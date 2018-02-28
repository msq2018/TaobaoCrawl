<?php

namespace App\Extensions;
use Encore\Admin\Grid\Tools\BatchAction;
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/2/27
 * Time: 9:27
 */
class AddCrawlQueue extends BatchAction
{

    protected $action;


    public function script()
    {
        $url = route("crawler.set_url");
        return <<<EOT
    
$('{$this->getElementClass()}').on('click', function() {
var ids =  selectedRows(); 
    if (ids.length<1){
        toastr.warning("请勾选需要爬取的店铺");
        return  false;
    }
    $.ajax({
        method: 'post',
        url: '{$url}',
        data: {
            _token:LA.token,
            ids: selectedRows(),
        },
        success: function (response) {
            $.pjax.reload('#pjax-container');
            toastr.success(response.message);
        }
    });
});

EOT;

    }


}