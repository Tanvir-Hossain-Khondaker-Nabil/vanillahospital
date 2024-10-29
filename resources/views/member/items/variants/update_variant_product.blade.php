<div class="col-md-12">


        <div class="form-group">
            <table width="100%" class="table table-response table-bordered">
                <thead>
                <tr>
                    <th width="25px">#SL</th>
                    <th width="100px"> {{__('common.variants')}} <br/>
                        {{ "(" }}
                        @foreach($variantData as $key => $variant)
                           {{ $variant->variant->title }}@if(!$loop->last)-@endif
                        @endforeach
                        {{ ")" }}
                    </th>
                    <th width="60px"> {{__('common.sku_code')}}</th>
                    <th width="85px"> {{__('common.purchase_price')}} </th>
                    <th width="70px"> {{__('common.selling_price')}} </th>
                    <th width="80px"> {{__('common.initial_stock_date')}} </th>
                    <th width="160px" class="px-3">
                        <div class="col-md-8">{{__('common.warehouse')}} </div>
                        <div class="col-md-4 ">{{__('common.initial_stock')}}</div>
                    </th>
                    <th width="100px">{{__('common.file')}}</th>
                </tr>
                </thead>
                <tbody id="warehouse">
@php
$i=1;
@endphp
                @foreach($variant_products as $key => $variant)
                        <tr style="border-bottom: 1px solid #9b9b9b;">
                            <th width="25px" style="vertical-align: middle;" class="text-center"> {{ $i++ }}</th>
                            <td width="100px" style="vertical-align: middle;">  <h5 class="my-0"> {{ $variant['name'] }} </h5></td>
                            <td width="100px" style="vertical-align: middle;">
                                <input type="hidden" name="item_ids[]" value="{{  $variant['id'] }}"/>
                                <input type="hidden" name="variants[]" value="{{  $variant['name'] }}"/>
                                {!! Form::text('skuCode[]',  $variant['skuCode'], [
                                    'id' => 'sku_code',
                                    'class' => 'form-control',
                                    'placeholder' => trans('common.enter_sku_code'),
                                ]) !!}
                            </td>
                            <td width="100px" style="vertical-align: middle;">
                                {!! Form::number('purchase_price[]', $variant['purchase_price'], [
                                'id' => 'purchase_price',
                                'class' => 'form-control',
                                'placeholder' => trans('common.enter_purchase_price'),
                                'step' => 'any',
                            ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                {!! Form::number('price[]', $variant['price'], [
                                        'id' => 'price',
                                        'class' => 'form-control',
                                        'placeholder' => trans('common.enter_price'),
                                        'required',
                                        'step' => 'any',
                                    ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                {!! Form::text('initial_date[]', $variant['initial_date'], [
                                 'class' => 'form-control initial_date',
                                 'placeholder' => trans('common.initial_stock_date'),
                                 'autocomplete' => 'off',
                             ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                @foreach($variant['initial_warehouses'] as $key2 => $initial_warehouses)
                                    @php
                                        $warehouse = $initial_warehouses->warehouse->title;
                                        $initial_qty = $initial_warehouses->qty;
                                        $warehouse_id = $initial_warehouses->warehouse_id;
                                    @endphp
                                    <div class="row mx-0">
                                        <div class="col-md-6 px-0 {{ !$loop->last ? 'border-bottom-1' : '' }} mb-2" style="min-height: 40px; vertical-align: middle;">

                                            <input type="hidden" name="warehouses[{{$key}}][]" value="{{ $warehouse_id }}"/>
                                            {!! Form::text('warehouses['.$key.'][]', $warehouse, [
                                               'class' => 'form-control', "disabled"
                                           ]) !!}
                                        </div>
                                        <div class="col-md-6 px-0 {{ !$loop->last ? 'border-bottom-1' : '' }} mb-2"  style="min-height: 40px; vertical-align: middle;">
                                            {!! Form::number('stock['.$key.']['.$warehouse_id.']', $initial_qty, [
                                               'id' => 'stock',
                                               'class' => 'form-control',
                                               'placeholder' => trans('common.enter_initial_stock'),
                                               'required',
                                               'step' => 'any',
                                           ]) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <div class="file btn btn-sm btn-primary">
                                    <i class="fa fa-upload"></i> {{__('common.upload')}}
                                    <input type="file" id='product_image' class="form-control" accept="image/jpeg"
                                           name="product_image[]"
                                           placeholder="Import image" onchange="getImagePreview(this)">
                                    <input type="hidden" id="front-image-url" value="">
                                    <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
                                        @if($variant['image'])
                                        <img src="{{ $variant['image'] }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                                        @endif
                                    </div>
                                </div>

                            </td>

                        </tr>

                @endforeach
                </tbody>

            </table>
        </div>

</div>
