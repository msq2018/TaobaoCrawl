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

    public function acceptSourceData(){
        $encoding = $_SERVER['HTTP_CONTENT_ENCODING'];
        if ($encoding == 'gzip') {//表示开启了gzip压缩
            $body = gzdecode(file_get_contents('php://input'));//读取并解压数据
            parse_str($body, $_POST);//将POST数据解析到全局变量$_POST中
        }
        file_put_contents("test.php",var_export($_POST,true),FILE_APPEND);
        $user_secret = Crawler::getModel()->getUserSecret();
        $sign2 = $_POST['sign2'];
        $url = $_POST['url'];
        $timestamp = $_POST['timestamp'];
        if (md5($url . $user_secret . $timestamp) === $sign2) {
            $data = $_POST['data'];
            // 处理数据
            // 最后, 你需要输出"data_key"
            echo $_POST['data_key'];
        } else {
            // 安全校验未通过拒绝响应
        }

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
