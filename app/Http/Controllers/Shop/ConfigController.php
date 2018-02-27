<?php

namespace App\Http\Controllers\Shop;

use App\Extensions\AddCrawlQueue;
use App\Models\TaobaoShopConfig;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ConfigController extends Controller
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

            $content->header('店铺设置');
            $content->description('管理淘宝店铺');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('编辑店铺');
            $content->description('修改店铺信息');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加店铺');
            $content->description('添加店铺信息');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(TaobaoShopConfig::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->shop_name("店铺名称");
            $grid->shop_link("店铺连接");
            $grid->platform("平台")->display(function ($platform){
                switch ($platform){
                    case "taobao":
                        return "淘宝";
                    case "tmall":
                        return "天猫";
                    case "1688" :
                        return "1688";
                    default:
                        return $platform;
                }
            });
            $grid->created_at("创建时间");
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->add('添加到爬虫队列',new AddCrawlQueue());
                });
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(TaobaoShopConfig::class, function (Form $form) {
            $form->text("shop_name","店铺名称")->rules("required");
            $form->url("shop_link","店铺连接")->rules("required");
            $form->select("platform","平台")->options([
                "taobao"=>"淘宝",
                "tmall"=>"天猫",
                "1688"=>"1688",
            ])->rules("required");
        });
    }
}
