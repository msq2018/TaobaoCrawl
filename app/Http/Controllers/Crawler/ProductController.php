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


    public function acceptSourceData(Request $request){
        $user_secret = Crawler::getModel()->getUserSecret();
        $sign2 = $request->post('sign2');
        $url = $request->post('url');
        $timestamp = $request->post('timestamp');
        $dataKey = $request->post("data_key");
        if (md5($url . $user_secret . $timestamp) === $sign2) {
            if ($data = $request->post('data',null)){
                $data = iconv("GB2312","UTF8",$data);
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
            }
        } else {
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
            $grid->id('ID')->sortable();
            $grid->created_at();
            $grid->updated_at();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
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
