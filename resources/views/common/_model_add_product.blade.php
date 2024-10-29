<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 1/19/2023
 * Time: 12:46 PM
 */

?>

<!-- Modal -->
<div id="addProduct" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 800px">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Item/Product</h4>
            </div>
            <form method="POST" enctype="multipart/form-data" id="submit-upload"  >
            <div class="modal-body">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="item_name">Product Name <span class="text-red"> * </span> </label>
                        {!! Form::text('item_name',null,['id'=>'item_name','class'=>'form-control','placeholder'=>'Enter Item Name', 'required']); !!}
                    </div>
                    <div class="form-group hidden">
                        <label for="item_name"> Product Code  </label>
                        {!! Form::hidden('productCode',$product_code,['id'=>'product_code','class'=>'form-control','placeholder'=>'Enter  Product Code', 'required']); !!}
                    </div>
                    <div class="form-group">
                        <label for="status">Product Category <span class="text-red"> * </span>  </label>
                        {!! Form::select('category_id',$categories, null,['id'=>'category_id','class'=>'form-control select2','placeholder'=>'Please select', 'required']); !!}
                    </div>
                    <div class="form-group">
                        <label for="status">Brand  </label>
                        {!! Form::select('brand_id',$brands, null,['id'=>'brand_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                    </div>
                    <div class="form-group">
                        <label for="status">Unit <span class="text-red"> * </span>  </label>
                        {!! Form::select('unit', $units, null,['id'=>'unit','class'=>'form-control','placeholder'=>'Please select Unit', 'required']); !!}
                    </div>

                    <div class="form-group hidden">
                        <label for="warranty"> Selling Price <span class="text-red"> * </span> </label>
                        {!! Form::number('price',0,['id'=>'price','class'=>'form-control','placeholder'=>'Enter Price', 'required', 'step'=>"any"]); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pb-1">
                        <label for="description">Description </label>
                        {!! Form::textarea('description',null,['id'=>'description','rows'=>'8', 'class'=>'form-control','placeholder'=>'Enter description']); !!}
                    </div>
                    <div class="form-group">
                        <label for="product_image">Product Image (Image must be JPG) </label>
                        {!! Form::file('product_image', null,['id'=>'product_image', 'accept'=>'image/*' , 'class'=>'form-control','placeholder'=>'Enter Title' , ]); !!}
                    </div>
                    <div class="form-group hidden">
                        <label for="warranty"> Opening Stock </label>
                        {!! Form::number('stock',0,['id'=>'stock','class'=>'form-control','placeholder'=>'Enter Initial Stock', 'required', 'step'=>"any"]); !!}
                    </div>

                    <div class="form-group hidden">
                        <label for="initial_balance">Opening Stock Date   </label>
                        {!! Form::text('initial_date', $initial_date,['id'=>'initial_date','class'=>'form-control initial_date' ,'placeholder'=>'Initial Stock Date ', 'autocomplete'=>'off']); !!}
                    </div>

                </div>
            </div>
            <div class="modal-footer px-5 pt-3">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="save-product"> Save </button>
            </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript">
        $(function () {

            // CKEDITOR.editorConfig = function( config ) {
            //     config.removeButtons = [ 'insert', 'JustifyCenter' ];
            // };

            CKEDITOR.replace('description');

            $('.select2').select2();

            $('#initial_date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });


            $('#submit-upload').submit(function (e) {
                e.preventDefault();

                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                    CKEDITOR.instances[instance].setData('');
                }
                var formData = new FormData(this);

                var url = "{{ route('member.items.store') }}";

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : formData,
                    dataType    : 'json',
                    encode      : true,
                    cache:false,
                    contentType: false,
                    processData: false,
                }).done(function(data) {

                    if(data.type=='success'){

                        $("#submit-upload")[0].reset();
                        $("#addProduct").modal('hide');
                        $("#new_product").append(
                            '<option data-content="'+data.items.unit+'" value="'+data.items.id+'" selected>'+data.items.item_name+'</option>');
                        $("#new_product").trigger('change.select2');

                        var products = [];
                        products = <?php print_r(json_encode($products)); ?>;

                        bootbox.alert("Product Add Successfully");
                    }else{
                        bootbox.alert("Product Name must be Unique!!");
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var error = $.parseJSON(jqXHR.responseText);
                    bootbox.alert("Product Name must be Unique!!");
                });
            });
        });

    </script>


@endpush
