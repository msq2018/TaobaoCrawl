<?php
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/2/27
 * Time: 16:59
 */

namespace App\Extensions;

use App\Models\Crawler;
use Encore\Admin\Admin;


class OperateCrawlerAppButton
{
    protected  $id ;

    protected $status ;

    public function __construct($id)
    {
        $this->id = $id;
        //$this->status = $status;
    }

    public function script(){
        $url = route("crawler.app.switch");
        return <<<EOF
        $(".operate-crawler-app").click(function(){
            var status = \$(this).data.('status');
            $.ajax({
                method: 'post',
                url: '{$url}',
                data: {
                    _token:LA.token,
                    status: status,
                },
                success: function () {
                    $.pjax.reload('#pjax-container');
                    toastr.success('操作成功');
                }
            });
        })
EOF;

    }

    protected function render()
    {
        Admin::script($this->script());
       $buttonTitle = "";$buttonClass = "btn-default";
       if ($appStatus = Crawler::getModel()->getAppStatus($this->id)){
            switch ($appStatus){
                case "stopped";
                    $buttonTitle = "启动";
                    $dataStatus = "starting";
                    $buttonClass = "btn-success";
                    break;
                case "pausing";
                    $buttonTitle = "继续";
                    $dataStatus = "resuming";
                    $buttonClass = "btn-success";
                    break;
                case "resuming";
                    $buttonTitle = "暂停";
                    $dataStatus = "pausing";
                    $buttonClass = "btn-success";
                    break;
                case "starting";
                    $buttonTitle = "停止";
                    $dataStatus = "stop";
                    $buttonClass = "btn-warning";
                    break;
            }
       };
        return "<a class='btn btn-sm btn-success {$buttonClass} operate-crawler-app' data-id='{$this->id}' data-status='{$dataStatus}'>{$buttonTitle}</a>";
    }

    public function __toString()
    {
        return $this->render();
    }


}