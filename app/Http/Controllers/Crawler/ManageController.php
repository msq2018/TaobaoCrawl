<?php

namespace App\Http\Controllers\Crawler;

use App\Extensions\CrawlerGraphQLButton;
use App\Extensions\OperateCrawlerAppButton;
use App\Models\Crawler;

use App\Models\TaobaoShopConfig;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ManageController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    public function appSwitch(Request $request){
        set_time_limit(120);
        $status = $request->post("status",null);
        $appId = $request->post('app_id',null);
        if (empty($appId)||empty($status)){
            return response()->json([
                "message"=>"status lost",
            ],400);
        }
        if (Crawler::getModel()->appSwitch($appId,$status)){
            return response()->json([
                "status"=>1,
                'successStatuses'=> ['stopped','running','paused'],
                "message"=>"crawler {$status} success "
            ]);
        }
        return response()->json([
            "status"=>0,
            "message"=>"crawler operate error",
        ]);
    }

    public function setScanUrl(Request $request){
        $shopIds = $request->post("ids");
        $platforms = [];
        foreach ($shopIds as $id){
           $shop = TaobaoShopConfig::find($id);
            if (Crawler::getModel()->addScanUrlToCrawler($shop->platform,$shop->shop_link)){
                $platforms[] = $shop->platform;
                $shop->status = TaobaoShopConfig::APPENDED_STATUS;
                $shop->save();
            }
        }
        $notRepeatPlatform = array_unique($platforms);
        foreach ($notRepeatPlatform as $item){
            if ($appId = Crawler::getModel()->getAppIdWithType($item)){
                Crawler::getModel()->appRestart($appId);
            };
        }
        return response()->json(["message"=>"shop append success"]);
    }

    public function getCrawlerResult(Request $request){
        $id = $request->post("id");
        $exitCode = Artisan::call('crawler:product', [
            'appId' => $id
        ]);
        return $exitCode;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Crawler::class, function (Grid $grid) {
            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->app_id('应用ID')->sortable();
            $grid->name("名称");
            $grid->info("描述");
            $grid->status("状态")->display(function ($status){
                return trans("admin.".$status);
            });
            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->append(new OperateCrawlerAppButton($actions->getKey(),[
                    "stopped"=>[
                        "title"=>"启动",
                        "operate"=>"start",
                        "class"=>"btn-success",
                    ],
                    "running"=>[
                        "title"=>"停止",
                        "operate"=>"stop",
                        "class"=>"btn-danger"
                    ],
                    "paused" =>[
                        "title"=>"停止",
                        "operate"=>"stop",
                        "class"=> "btn-danger",
                    ]
                ]));
                $actions->append(new OperateCrawlerAppButton($actions->getKey(),[
                    "running" => [
                        "title"=>"暂停",
                        "operate"=>"pause",
                        "class"=>"btn-info",
                    ],
                    "paused" =>[
                        "title"=>"继续",
                        "operate"=>"resume",
                        "class"=> "btn-waring",
                    ]
                ]));
                //$actions->append(new CrawlerGraphQLButton($actions->getKey()));
            });

        });
    }
}
