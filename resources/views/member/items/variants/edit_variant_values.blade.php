
    <div class="form-group col-md-4">
        <label for="status">{{__('common.select')}} {{ $variant_values->variant->title }} <span class="text-red"> * </span> </label>

        <select disabled name='variant_value_{{ $variant_values->variant->id }}[]' id='variant_value_{{ $variant_values->variant->id }}' multiple class="form-control select2">
            <option value='' disabled>{{__('common.select')}} {{ $variant_values->variant->title }}  </option>

            @foreach($variant_values->variant->variant_values as $value)
                <option value='{{ $value->id }}' {{ isset($variant_values) ? in_array( $value->id, explode(",",$variant_values->values)) ? "selected" : '' : '' }}>{{ $value->name }}</option>
            @endforeach

        </select>
    </div>

