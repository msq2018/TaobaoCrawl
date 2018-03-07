<?php

namespace App\Http\Controllers\Catalog;

use App\Models\Catalog\Product;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Crawler\Product as CrawlerProduct;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    use ModelForm;

    protected $crawlerProductId;

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

    public function storeFormCrawler(Request $request){
        $data = $request->all();
        $builder = $this->publishCrawlerProductForm()->builder();
        $messageBag = new MessageBag();
        foreach ($builder->fields() as $field){
            if (!$validator = $field->getValidator($data)) {
                continue;
            }
            if (($validator instanceof Validator) && !$validator->passes()) {
                $messageBag = $messageBag->merge($validator->messages());
            }
        }
        if ($messageBag->any() === true){
            return back()->withInput()->withErrors($messageBag);
        }
        if (Product::getModel()->saveFormCrawlerProduct($data)){
            admin_toastr(trans('admin.save_succeeded'));
        }
        return redirect(route("product.index"));
    }

    public function publishCrawlerProduct($id){
        $this->crawlerProductId = $id;
        return Admin::content(function (Content $content) use($id){
            $content->header('发布产品');
            $content->description('编辑爬取的产品');
            $content->body($this->publishCrawlerProductForm()->edit($id));
        });
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Product::class, function (Grid $grid) {

            $grid->column('id','ID')->sortable();
            $grid->column("name","产品名称");
            $grid->column("images","产品主图")->display(function ($galleryImages){
                if (isset($galleryImages[0])){
                    return "<img src='{$galleryImages[0]}' height='80'/>";
                }
                return "";
            });
            $grid->column("price","原价");
            $grid->column("special_price","促销价")->display(function ($specialPrice){
                
            });
            $grid->column("status","状态")->display(function ($status){
                return $status?"开启":"关闭";
            });

            $grid->created_at("创建时间");
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


        });
    }

    protected function publishCrawlerProductForm()
    {
        return Admin::form(CrawlerProduct::class, function (Form $form) {
            $form->setAction(route("store.product.form.crawler",(int)$this->crawlerProductId));
            $form->tools(function ($tools){
                $resource = route("product.index");
                $newListButton = <<<EOT
<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="$resource" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
</div>
EOT;
                $tools->disableListButton();
                $tools->add($newListButton);
            });
            $form->tab('基础', function ($form) {
                $form->hidden('id');
                $form->multipleSelect("category_id","产品分类")->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
                $form->text('name',"产品名称")->rules("required");
                $form->text('sku')->rules("required");
                $form->number('qty')->rules("required|min:1");
                $form->currency('origin_price')->rules("required|min:0");
                $form->currency("special_price");
                $form->switch("status")->states([
                    'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
                    'off' => ['value' => 2, 'text' => '关闭', 'color' => 'danger'],
                ]);
            })->tab("属性",function ($form){
                $form->options('params');
            })->tab('图片', function ($form) {
                    $form->multipleImage("gallery_images","产品图片")
                    ->removable();
            })->tab('描述', function ($form) {
                $form->editor("short_description","短描述")->simple()->rules("required");
                $form->editor("detail","描述");
            });

        });
    }
}
