@foreach($variants as $variant)

    <div class="form-group col-md-4">
        <label for="status">Select {{ $variant->title }} <span class="text-red"> * </span> </label>

        <select name='variant_value_{{ $variant->id }}[]' id='variant_value_{{ $variant->id }}' multiple class="form-control select2">
            <option value='' disabled>Select {{ $variant->title }}  </option>

            @foreach($variant->variant_values as $value)
                <option value='{{ $value->id }}'>{{ $value->name }}</option>
            @endforeach

        </select>
    </div>

@endforeach
