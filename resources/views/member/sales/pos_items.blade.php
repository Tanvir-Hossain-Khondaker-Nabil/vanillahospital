@foreach($products as $key => $value)
    <a  onfocus="this.blur()"  tabindex="-1" href="javascript:void(0)" class="add-row" data-target="{{  $value->id  }}" data-value="{{ $value->item_name }}" data-unit="{{ $value->unit }}" data-pcode="{{ $value->productCode }}" data-skucode="{{ $value->skuCode }}" data-stock="{{ $value->stock_details->stock }}" >
        <div class="col-md-3 px-0 py-3  mb-3 card-body text-center" style="border:1px solid lightblue; height: 130px;">
            <div class="w-100">
                <img src="{{ $value->product_image ? asset($value->product_image_path) : asset('public/pos_assets/images/item.png') }}" style="width: 50px;height: 40px;" class="mb-2">
            </div>
            <div class="w-100 text-black px-1">
                {{ limit_words($value->item_name,5) }}
            </div>
        </div>
    </a>
@endforeach
