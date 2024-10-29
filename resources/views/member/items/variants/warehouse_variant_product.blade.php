<div class="col-md-12">

    <div class="form-group">
        <label for="warranty"> Purchase Price </label>
        {!! Form::number('purchase_price', null, [
            'id' => 'purchase_price',
            'class' => 'form-control',
            'placeholder' => 'Enter purchase Price',
            'step' => 'any',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="item_name">SkuCode <span class="text-red"> </span> </label>
        {!! Form::text('skuCode', null, [
            'id' => 'sku_code',
            'class' => 'form-control',
            'placeholder' => 'Enter Sku Code',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="warranty"> Opening Stock </label>
        {!! Form::number('stock', null, [
            'id' => 'stock',
            'class' => 'form-control',
            'placeholder' => 'Enter Initial Stock',
            'required',
            'step' => 'any',
        ]) !!}
    </div>




    <div class="form-group">
        <label for="initial_balance">Opening Stock Date </label>
        {!! Form::text('initial_date', null, [
            'id' => 'date',
            'class' => 'form-control initial_date',
            'placeholder' => 'Initial Stock Date ',
            'autocomplete' => 'off',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="warranty"> Selling Price <span class="text-red"> * </span> </label>
        {!! Form::number('price', null, [
            'id' => 'price',
            'class' => 'form-control',
            'placeholder' => 'Enter Price',
            'required',
            'step' => 'any',
        ]) !!}
    </div>

    @if (config('settings.warehouse'))

        <div class="form-group">
            <table width="100%">
                <thead>
                <tr>
                    <th width="350px"> Warehouse</th>
                    <th width="150px" class="px-3">QTY</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="warehouse">
                @if (isset($modal) && count($modal->warehouses) > 0)
                    @foreach ($modal->warehouses as $key => $value)
                        <tr>
                            <td class="pt-2">
                                {!! Form::select('warehouse_id[]', $warehouses, $value->warehouse_id, [
                                    'class' => 'form-control select2',
                                    'required',
                                    'placeholder' => 'Select Warehouse',
                                ]) !!}
                            </td>
                            <td class="pt-2 px-3">
                                {!! Form::number('unload_qty[]', $value->qty, [
                                    'class' => 'form-control  text-center',
                                    'step' => 'any',
                                    'required',
                                ]) !!}
                            </td>
                            <td class="pt-2">
                                @if ($key == 0)
                                    <a class="btn btn-primary btn-sm multi-row"
                                       href="javascript:void(0)"
                                       data-content="0"> <i class="fa fa-clone"></i> </a>
                                @else
                                    <a class="btn btn-danger delete-row btn-sm"
                                       href="javascript:void(0)"> <i
                                            class="fa fa-trash"></i> </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td> {!! Form::select('warehouse_id[]', $warehouses, null, [
                                'class' => 'form-control select2',
                                'required',
                                'placeholder' => 'Select Warehouse',
                            ]) !!}</td>
                        <td class="px-3">{!! Form::number('unload_qty[]', null, ['class' => 'form-control  text-center', 'step' => 'any', 'required']) !!}</td>
                        <td><a class="btn btn-primary btn-sm multi-row" href="javascript:void(0)"
                               data-content="0">
                                <i class="fa fa-clone"></i> </a></td>
                    </tr>
                @endif
                </tbody>

            </table>
        </div>

    @endif
</div>
