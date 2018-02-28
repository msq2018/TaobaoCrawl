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

    protected $operating = [];

    public function __construct($id,$operating)
    {
        $this->id = $id;
        $this->operating = $operating;
    }

    public function script(){
        $url = route("crawler.app.switch");
        $selectStatusUrl = route("crawler.get_status");
        return <<<EOF
        $(".operate-crawler-app").click(function(){
            var status = \$(this).data('status'),appId = \$(this).data("id");
            $.ajax({
                method: 'post',
                url: '{$url}',
                data: {
                    _token:LA.token,
                    app_id : appId,
                    status: status,
                },
                success: function (response) {
                    if (response.status==1){
                    $.pjax.reload('#pjax-container');
                    toastr.success(response.message);
                    }
                }
            });
        })
  
EOF;

    }

    protected function render()
    {
        Admin::script($this->script());
       $buttonTitle = "";$buttonClass = "btn-default";$dataStatus = null;
       if ($appStatus = Crawler::getModel()->getCrawlerStatus($this->id)){
           if (isset($this->operating[$appStatus])){
               $operate = $this->operating[$appStatus];
               return "<a class='btn btn-sm btn-success {$operate['class']} operate-crawler-app' data-id='{$this->id}' data-status='{$operate['operate']}' style='margin-left: 5px;'>{$operate['title']}</a>";
           }
       };
        return "";
    }

    public function __toString()
    {
        return $this->render();
    }


}