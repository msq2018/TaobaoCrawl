<?php

namespace App\Http\Controllers\Crawler;

use App\Extensions\ImportDataSourceButton;
use App\Models\Crawler;
use App\Models\Crawler\Product;

use App\Models\TaobaoShopConfig;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;


class ProductController extends Controller
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

            $content->header('产品库');
            $content->description('来自爬虫的产品数据');

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

            $content->header('产品库');
            $content->description('来自爬虫的产品数据');

            $content->body($this->form());
        });
    }


    public function acceptSourceData(Request $request){
        $user_secret = Crawler::getModel()->getUserSecret();
        $sign2 = $request->post('sign2');
        $url = $request->post('url');
        $timestamp = $request->post('timestamp');
        $dataKey = $request->post("data_key");
        if ($data = $request->post('data',null)){
            //$data = iconv("GB2312","UTF8",$data);
            $data = json_decode($data,true);
            $product =new Product();
            $product->product_id = (int)$data['product_id'];
            $product->name =  $data['name'];
            $product->product_link =  $data['link'];
            $product->origin_price = $data['origin_price'];
            $product->gallery_images = serialize($data['gallery_images']);
            $product->params = serialize($data['params']);
            $product->attributes = serialize($data['attributes']);
            $product->detail = htmlspecialchars($data['detail']);
            $product->shop_id = $data['shop_id'];
            $product->shop_name = $data['shop_name'];
            $product->shop_link = $data['shop_link'];
            $product->platform = "taobao";
            $product->save();
        }else {
            return response("validate error",403);
        }

        return response($dataKey);
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Product::class, function (Grid $grid) {
            $grid->disableCreateButton();
            $grid->column('product_id',"产品Id")->sortable();
            $grid->column("name","产品名称");
            $grid->column("gallery_images","产品主图")->display(function ($galleryImages){
                $images =  unserialize($galleryImages);
                if (isset($images[0])){
                    return "<img src='{$images[0]}' height='80'/>";
                }
                return "";
            });
            $grid->column("origin_price","原价");
            $grid->column("shop_id","店铺ID");
            $grid->column("shop_name","店铺名称");
            $grid->column("product_link","产品连接")->display(function ($productLink){
                return "<a href='{$productLink}' class='btn btn-success btn-sm'>查看产品</a>";
            });
            $grid->column("shop_link","店铺连接")->display(function ($shopLink){
                return "<a href='{$shopLink}' class='btn btn-primary btn-sm'>查看店铺</a>";
            });
            $grid->created_at();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
            /*  $grid->tools(function ($tools) {
                  $tools->append(new ImportDataSourceButton(TaobaoShopConfig::PLATFORM_TAOBAO));
              });*/
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Product::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
