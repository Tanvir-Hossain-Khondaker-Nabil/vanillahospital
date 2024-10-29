<div class="col-md-12">


        <div class="form-group">
            <table width="100%" class="table table-response table-bordered">
                <thead>
                <tr>
                    <th width="25px">#SL</th>
                    <th width="100px"> Variants <br/>
                        {{ "(" }}
                        @foreach($variantData as $key => $variant)
                           {{ $variant['id']->title }}@if(!$loop->last)-@endif
                        @endforeach
                        {{ ")" }}
                    </th>
                    <th width="60px"> Sku Code</th>
                    <th width="85px"> Purchase Price </th>
                    <th width="70px"> Selling Price </th>
                    <th width="80px"> Initial Stock Date </th>
                    <th width="160px" class="px-3">
                        <div class="col-md-8">Warehouse </div>
                        <div class="col-md-4 ">Initial Stock</div>
                    </th>
                    <th width="100px">File</th>
                </tr>
                </thead>
                <tbody id="warehouse">
@php
$i=1;
@endphp
                @foreach($variantArray as $key => $variant)
                        <tr style="border-bottom: 1px solid #9b9b9b;">
                            <th width="25px" style="vertical-align: middle;" class="text-center"> {{ $i++ }}</th>
                            <td width="100px" style="vertical-align: middle;">  <h5 class="my-0"> {{ $variant }} </h5></td>
                            <td width="100px" style="vertical-align: middle;">
                                <input type="hidden" name="variants[]" value="{{ $variant }}"/>
                                <input type="hidden" name="variantIds[]" value="{{ $variantIDArray[$key] }}"/>
                                {!! Form::text('skuCode[]', null, [
                                    'id' => 'sku_code',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Sku Code',
                                ]) !!}
                            </td>
                            <td width="100px" style="vertical-align: middle;">
                                {!! Form::number('purchase_price[]', null, [
                                'id' => 'purchase_price',
                                'class' => 'form-control',
                                'placeholder' => 'Enter purchase Price',
                                'step' => 'any',
                            ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                {!! Form::number('price[]', null, [
                                        'id' => 'price',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Price',
                                        'required',
                                        'step' => 'any',
                                    ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                {!! Form::text('initial_date[]', null, [
                                 'class' => 'form-control initial_date',
                                 'placeholder' => 'Initial Stock Date ',
                                 'autocomplete' => 'off',
                             ]) !!}
                            </td>
                            <td class="pt-2 px-3" style="vertical-align: middle;">
                                @foreach($warehouses as $key2 => $warehouse)

                                    <div class="row mx-0">
                                        <div class="col-md-6 px-0 {{ !$loop->last ? 'border-bottom-1' : '' }} mb-2" style="min-height: 40px; vertical-align: middle;">

                                            <input type="hidden" name="warehouses[{{$key}}][]" value="{{ $key2 }}"/>
                                            {!! Form::text('warehouses['.$key.'][]', $warehouse, [
                                               'class' => 'form-control', "disabled"
                                           ]) !!}
                                        </div>
                                        <div class="col-md-6 px-0 {{ !$loop->last ? 'border-bottom-1' : '' }} mb-2"  style="min-height: 40px; vertical-align: middle;">
                                            {!! Form::number('stock['.$key.'][]', null, [
                                               'id' => 'stock',
                                               'class' => 'form-control',
                                               'placeholder' => 'Enter Initial Stock',
                                               'required',
                                               'step' => 'any',
                                           ]) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <div class="file btn btn-sm btn-primary">
                                    <i class="fa fa-upload"></i> Upload
                                    <input type="file" id='product_image' class="form-control" accept="image/jpeg"
                                           name="product_image[]"
                                           placeholder="Import image" onchange="getImagePreview(this)">
                                    <input type="hidden" id="front-image-url" value="">
                                    <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
                                    </div>
                                </div>

                            </td>

                        </tr>

                @endforeach
                </tbody>

            </table>
        </div>

</div>
