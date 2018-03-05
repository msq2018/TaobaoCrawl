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

    public function publishCrawlerProduct($id){
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

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
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
            $form->tab('基础', function ($form) {
                $form->text('name');
                $form->text('sku');
                $form->number('qty');
                $form->currency('price');
                $form->currency("special_price");
                $form->editor("short_description");
                $form->switch("status")->states([
                    'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
                ]);
            })->tab('图片', function ($form) {
                $form->multipleImage("images","产品图片")
                    ->removable();

            })->tab('描述', function ($form) {
                $form->editor("description");

            })->tab("分类",function ($form){
                $form->multipleSelect("category_id")->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
            });

        });
    }

    private function publishCrawlerProductForm()
    {
        return Admin::form(CrawlerProduct::class, function (Form $form) {
            $form->tab('基础', function ($form) {
                $form->multipleSelect("category_id","产品分类")->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
                $form->text('name',"产品名称");
                $form->text('sku');
                $form->number('qty');
                $form->currency('origin_price');
                $form->currency("special_price");
                $form->switch("status")->states([
                    'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
                ]);
            })->tab("属性",function ($form){

            })->tab('图片', function ($form) {
                    $form->multipleImage("gallery_images","产品图片")
                    ->removable();
            })->tab('描述', function ($form) {
                $form->editor("short_description","短描述")->simple();
                $form->editor("description","描述");

            });

        });
    }
}
