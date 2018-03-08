<?php
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/3/8
 * Time: 8:52
 */

namespace App\Extensions;




use Encore\Admin\Admin;

class CrawlerGraphQLButton
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script(){

        $url = route('crawler.get_crawler_result');
        return <<<EOF
        $("#update_crawler_result").click(function(){
           $.post("{$url}",{_token:LA.token,id:"{$this->id}"});
           toastr.success("数据已开始更新，请移步爬虫产品库查看，更新会持续几分钟时间，请勿重复点击!");
        });
EOF;
    }

    public function render()
    {
        Admin::script($this->script());
        return "<a href='#' style='margin-left: 5px' id='update_crawler_result' class='btn btn-default btn-sm'>更新爬取结果</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}