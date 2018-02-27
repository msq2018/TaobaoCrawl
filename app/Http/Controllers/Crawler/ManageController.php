<?php

namespace App\Http\Controllers\Crawler;

use App\Extensions\OperateCrawlerAppButton;
use App\Models\Crawler;

use App\Models\TaobaoShopConfig;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    public function appSwitch(Request $request){
        $status = $request->post("status",null);
        if (empty($status)){
            return response()->json([
                "message"=>"status lost",
            ],400);
        }
        if (Crawler::getModel()->appSwitch($status)){

        }
    }

    public function setScanUrl(Request $request){
        $shopIds = $request->post("ids");
        foreach ($shopIds as $id){
            $shop =  TaobaoShopConfig::find($id);
            Crawler::getModel()->addScanUrlToCrawler($shop->platform,$shop->shop_link);
        }

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
                $actions->append(new OperateCrawlerAppButton($actions->getKey()));

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
        return Admin::form(Crawler::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
