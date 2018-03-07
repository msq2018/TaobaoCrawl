<?php
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/3/6
 * Time: 14:12
 */

namespace App\Extensions\Form;

use Encore\Admin\Form\Field;

class Options extends Field
{

    protected $view = "admin.form.options";

    protected static $js = [
      "/vendor/template.js-0.7.1/template.js"
    ];

    public function __construct($column, array $arguments)
    {

        parent::__construct($column, $arguments);
    }

    public function render()
    {
        $this->script = $this->getScript();
        return parent::render(); 
    }

    public function getScript()
    {
        return <<<EOT
        //title
        template.config({sTag: '{#', eTag: '#}'});
        var parentId = $('.option_box').length?$('.option_box').length:0,column = "{$this->column}",rowId = 0;
        $('#add-option').click(function (event) {
            var newOptionHtml = template($("#new-option").html(),{
                id : parentId,
                column: column
            });
            $("#option-container").append(newOptionHtml);
            parentId++;
        })
        $(document).on("click",".option_remove",function (event) {
            $(this).parents(".option_box").remove();
        })
        //value
        $(document).on("change",".option_input_type",function (event) {
            var type = $(this).val();
            var typeHtml = $("#type-"+type).html();
            var valueBox = $(this).parents(".option_box").find(".option-value-box");
            if (valueBox.find(".content-row").length != 0){
                return false;
            }
            var firstRow = template($("#type-"+type+"-row").html(),{
                parentId : parentId,
                rowId :rowId,
                column : column,
            });
            typeHtml = template(typeHtml,{
                firstRow: firstRow,
            })
            valueBox.html(typeHtml);
        })
        $(document).on("click",".option-value_remove_row,.option-value_add_row",function (event) {
            if ($(this).attr("action") == 'remove'){
                $(this).parents("tr").remove();
            }else if($(this).attr("action") == 'add'){
                var type = $(this).parents("table").data('type');
                var rowHtml = $("#type-"+type+"-row").html();
                rowHtml = template(rowHtml,{
                    parentId : parentId,
                    rowId :rowId,
                    column : column,
                })
                $(this).parents("table").find('tbody').append(rowHtml);
                rowId++;
            }
        })     
EOT;
    }
}