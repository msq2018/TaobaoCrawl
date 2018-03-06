<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-8">

        @include('admin::form.error')
        <div class="panel panel-info" id="{{$column}}-options-box">
            <div class="panel-heading" style="overflow: hidden">
                <a class="pull-right btn btn-xs btn-success" id="add-option">添加选项</a>
            </div>
            <div class="panel-body" id="option-container">
                @if(!empty($value)&& is_array($value))
                    @foreach ($value as $key=>$item)
                        <div class="panel panel-primary option_box" data-id="{{$key}}">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-10 ">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="{{$column}}-{{$key}}_option">Title</label>
                                                    <input type="text" class="form-control" name="{{$column}}[{{$key}}][title][title]" id="{{$column}}-{{$key}}-option" placeholder="Title" value="{{$item["label"]}}">
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="{{$column}}-{{$key}}-option_input_type">Input Type</label>
                                                    <select class="form-control option_input_type " name="{{$column}}[{{$key}}][title][input_type]" id="{{$column}}-{{$key}}-option_input_type">
                                                        <option value="">Select Type</option>
                                                        <option value="drop-down">Drop-down</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="{{$column}}-{{$key}}-option_is_required">Is Required</label>
                                                    <select class="form-control" name="{{$column}}[{{$key}}][title][required]" id="{{$column}}-{{$key}}-option_is_required">
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="{{$column}}-{{$key}}-option_sort_order">Sort Order</label>
                                                    <input type="text" class="form-control" name="{{$column}}[{{$key}}][title][sort]" id="{{$column}}-{{$key}}-option_title_sort_order" placeholder="Sort Order">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <a class="btn btn-danger btn-xs pull-right option_remove"><i class="fa fa-remove"></i></a>
                                    </div>
                                </div>
                                <div class="option-value-box table-responsive " >
                                    @if(!empty($item['value']))
                                        <table class="table table-bordered" data-type=drop-down >
                                            <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Price</th>
                                                <th>Price Type</th>
                                                <th>SKU</th>
                                                <th>Sort Order</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(is_array($item['value']))
                                                @foreach($item['value'] as $k=> $value)
                                                    <tr class="content-row">
                                                        <td><input type="text" class="form-control" name="{{$column}}[{{$key}}][value][{{$k}}][title]" id="value_title" placeholder="Title" value="{{$value}}"></td>
                                                        <td><input type="text" class="form-control" name="{{$column}}[{{$key}}][value][{{$k}}][price]" id="value_price" placeholder="Price"></td>
                                                        <td>
                                                            <select name="" class="form-control" name="{{$column}}[{{$key}}][value][{{$k}}][price_type]" id="value_price_type">
                                                                <option value="">Fixed</option>
                                                                <option value="">Percent</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control" name="{{$column}}[{{$key}}][value][{{$k}}][sku]" id="value_sku" placeholder="SKU"></td>
                                                        <td><input type="text" class="form-control" name="{{$column}}[{{$key}}][value][{{$k}}][sort]" id="value_sort" placeholder="Sort Order"></td>
                                                        <td><a class="btn btn-xs btn-danger option-value_remove_row" action="remove">删除</a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <a class="btn btn-xs btn-success option-value_add_row" action="add">添加新行</a>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    @endif

                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>
<script type="application/javascript">
    $(function () {
        //title
        var parentId = $('.option_box').length?$('.option_box').length:0,column = "params",rowId = 0;
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
            if ($(".content-row").length != 0){
                return false;
            }
            var type = $(this).val();
            var typeHtml = $("#type-"+type).html();
            var valueBox = $(this).parents(".option_box").find(".option-value-box");
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
    })
</script>
<script type="text/html" id="new-option">
    <div class="panel panel-primary option_box" data-id="<%=id%>" >
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-10 ">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="<%=column%>_<%=id%>_option">Title</label>
                                    <input type="text" class="form-control" name="<%=column%>[<%=id%>][title][title]" id="<%=column%>-<%=id%>-option" placeholder="Title">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="<%=column%>-<%=id%>-option_input_type">Input Type</label>
                                    <select class="form-control option_input_type " name="<%=column%>[<%=id%>][title][input_type]" id="<%=column%>-<%=id%>-option_input_type">
                                        <option value="">Select Type</option>
                                        <option value="drop-down">Drop-down</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="<%=column%>-<%=id%>-option_is_required">Is Required</label>
                                    <select class="form-control" name="<%=column%>[<%=id%>][title][required]" id="<%=column%>-<%=id%>-option_is_required">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="<%=column%>-<%=id%>-option_sort_order">Sort Order</label>
                                    <input type="text" class="form-control" name="<%=column%>[<%=id%>][title][sort]" id="<%=column%>-<%=id%>-option_title_sort_order" placeholder="Sort Order">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <a class="btn btn-danger btn-xs pull-right option_remove"><i class="fa fa-remove"></i></a>
                    </div>
                </div>
                <div class="option-value-box table-responsive " >

                </div>

            </div>
        </div>

</script>
<script type="text/html" id="type-drop-down">
    <table class="table table-bordered" data-type=drop-down >
        <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Price Type</th>
            <th>SKU</th>
            <th>Sort Order</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <%:=firstRow%>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">
                <a class="btn btn-xs btn-success option-value_add_row" action="add">添加新行</a>
            </td>
        </tr>
        </tfoot>
    </table>
</script>
 <script type="text/html" id="type-drop-down-row">
  <tr class="content-row">
            <td><input type="text" class="form-control" name="<%=column%>[<%=parentId%>][value][<%=rowId%>][title]" id="value_title" placeholder="Title"></td>
            <td><input type="text" class="form-control" name="<%=column%>[<%=parentId%>][value][<%=rowId%>][price]" id="value_price" placeholder="Price"></td>
            <td>
                <select name="" class="form-control" name="<%=column%>[<%=parentId%>][value][<%=rowId%>][price_type]" id="value_price_type">
                    <option value="">Fixed</option>
                    <option value="">Percent</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="<%=column%>[<%=parentId%>][value][<%=rowId%>][sku]" id="value_sku" placeholder="SKU"></td>
            <td><input type="text" class="form-control" name="<%=column%>[<%=parentId%>][value][<%=rowId%>][sort]" id="value_sort" placeholder="Sort Order"></td>
            <td><a class="btn btn-xs btn-danger option-value_remove_row" action="remove">删除</a></td>
        </tr>
 </script>