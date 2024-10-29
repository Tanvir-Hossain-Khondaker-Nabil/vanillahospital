@extends('frontend.layouts.master')
@section('content')
<!-- Service Area Start Here -->
<section class="service-layout1 bg-accent s-space-custom2">
    <div class="container">
        <div class="section-title-dark">
            <h1>Welcome To ClassiPost Classified</h1>
            <p>Browse Our Top Categories</p>
        </div>
        <div class="row">
            @foreach ($categories as $category)
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 item-mb">
                <div class="service-box1 bg-body text-center">
                    <img src="{{asset('public/frontend/img/service/service1.png')}}" alt="service" class="img-fluid">
                    <h3 class="title-medium-dark mb-none">
                        <a href="category-grid-layout1.html">{{ $category->display_name }}</a>
                    </h3>
                    <div class="view">100</div>
                    <p>Emply dummy text of the printing and taypng industrxt ever sincknown.</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Service Area End Here -->
<!-- Products Area Start Here -->
<section class="bg-body s-space-default">
    <div class="container">
        <div class="section-title-dark">
            <h2>Buy &amp; Sell Online Products</h2>
            <p>Browse To Our Top Products</p>
        </div>
    </div>
    <div class="container" id="isotope-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="isotope-classes-tab isotop-btn">
                    <a href="#" data-filter=".new" class="current">New</a>
                    <a href="#" data-filter=".featured">Featured</a>
                    <a href="#" data-filter=".random">Random</a>
                </div>
            </div>
        </div>
        <div id="category-view" class="category-grid-layout2">
            <div class="row featuredContainer">

                @foreach ($items as $item)
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 new featured">
                    <div class="product-box item-mb zoom-gallery">
                        <div class="item-mask-wrapper">
                            <div class="item-mask bg-box">
                                <img src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" alt="categories" class="img-fluid">
                                <div class="trending-sign" data-tips="Featured"> 
                                    <i class="fa fa-bolt" aria-hidden="true"></i> 
                                </div>
                                <div class="title-ctg">{{ $item->category->display_name }}</div>
                                <ul class="info-link">
                                    <li><a href="{{ route('frontend.product',$item->id) }}" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                    <li><a href="{{ route('frontend.product',$item->id) }}"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                </ul>
                                <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
                            </div>
                        </div>
                        <div class="item-content">
                            <div class="title-ctg">Clothing</div>
                            <h3 class="short-title"><a href="{{ route('frontend.product',$item->id) }}">{{ $item->item_name }}</a></h3>
                            <h3 class="long-title"><a href="single-product1.html">Men's Basic Cotton T-Shirt</a></h3>
                            <ul class="upload-info">
                                <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>{{ date('d-M-Y')}}</li>
                                <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                                <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                            </ul>
                            <p>{{ $item->description }}</p>
                            <div class="price">{{ $item->price }} tk</div>
                            <a href="single-product-layout1.html" class="product-details-btn">Details</a>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- <div class="col-lg-4 col-md-6 col-sm-6 col-12 featured random">
                        <div class="product-box item-mb zoom-gallery">
                            <div class="item-mask-wrapper">
                                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product2.png')}}" alt="categories" class="img-fluid">
                <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                <div class="title-ctg">Electronics</div>
                <ul class="info-link">
                    <li><a href="img/product/product2.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                    <li><a href="single-product-layout2.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                </ul>
                <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
            </div>
        </div>
        <div class="item-content">
            <div class="title-ctg">Clothing</div>
            <h3 class="short-title"><a href="single-product2.html">Patriot Phone</a></h3>
            <h3 class="long-title"><a href="single-product2.html">HTC Desire Patriot Mobile Smart Phone</a></h3>
            <ul class="upload-info">
                <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
            </ul>
            <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
            <div class="price">$250</div>
            <a href="single-product-layout2.html" class="product-details-btn">Details</a>
        </div>
    </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 random new">
        <div class="product-box item-mb zoom-gallery">
            <div class="item-mask-wrapper">
                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product3.png')}}" alt="categories" class="img-fluid">
                    <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                    <div class="title-ctg">Electronics</div>
                    <ul class="info-link">
                        <li><a href="img/product/product3.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                        <li><a href="single-product-layout3.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                    </ul>
                    <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
                </div>
            </div>
            <div class="item-content">
                <div class="title-ctg">Clothing</div>
                <h3 class="short-title"><a href="single-product3.html">Smart LED TV</a></h3>
                <h3 class="long-title"><a href="single-product3.html">TCL 55-Inch 4K Ultra HD Roku Smart LED TV</a></h3>
                <ul class="upload-info">
                    <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                    <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                    <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                </ul>
                <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
                <div class="price">$800</div>
                <a href="single-product-layout3.html" class="product-details-btn">Details</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 featured new random">
        <div class="product-box item-mb zoom-gallery">
            <div class="item-mask-wrapper">
                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product4.png')}}" alt="categories" class="img-fluid">
                    <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                    <div class="title-ctg">Clothing</div>
                    <ul class="info-link">
                        <li><a href="img/product/product4.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                        <li><a href="single-product-layout1.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                    </ul>
                    <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
                </div>
            </div>
            <div class="item-content">
                <div class="title-ctg">Clothing</div>
                <h3 class="short-title"><a href="single-product1.html">Headphones</a></h3>
                <h3 class="long-title"><a href="single-product1.html">Basics Lightweight On-Ear Headphones</a></h3>
                <ul class="upload-info">
                    <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                    <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                    <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                </ul>
                <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
                <div class="price">$15</div>
                <a href="single-product-layout1.html" class="product-details-btn">Details</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 new">
        <div class="product-box item-mb zoom-gallery">
            <div class="item-mask-wrapper">
                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product5.png')}}" alt="categories" class="img-fluid">
                    <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                    <div class="title-ctg">Clothing</div>
                    <ul class="info-link">
                        <li><a href="img/product/product5.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                        <li><a href="single-product-layout2.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                    </ul>
                    <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
                </div>
            </div>
            <div class="item-content">
                <div class="title-ctg">Clothing</div>
                <h3 class="short-title"><a href="single-product2.html">Handbags</a></h3>
                <h3 class="long-title"><a href="single-product2.html">MMK collection Women Fashion Matching Satchel handbags</a></h3>
                <ul class="upload-info">
                    <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                    <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                    <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                </ul>
                <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
                <div class="price">$15</div>
                <a href="single-product-layout2.html" class="product-details-btn">Details</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 featured new">
        <div class="product-box item-mb zoom-gallery">
            <div class="item-mask-wrapper">
                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product6.png')}}" alt="categories" class="img-fluid">
                    <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                    <div class="title-ctg">Clothing</div>
                    <ul class="info-link">
                        <li><a href="img/product/product6.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                        <li><a href="single-product-layout3.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                    </ul>
                    <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png')}}" alt="symbol" class="img-fluid"> </div>
                </div>
            </div>
            <div class="item-content">
                <div class="title-ctg">Clothing</div>
                <h3 class="short-title"><a href="single-product3.html">Classic Watch</a></h3>
                <h3 class="long-title"><a href="single-product3.html">Men's Classic Sport Watch with Black Band</a></h3>
                <ul class="upload-info">
                    <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                    <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                    <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                </ul>
                <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
                <div class="price">$15</div>
                <a href="single-product-layout3.html" class="product-details-btn">Details</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 random new">
        <div class="product-box item-mb zoom-gallery">
            <div class="item-mask-wrapper">
                <div class="item-mask bg-box"><img src="{{asset('public/frontend/img/product/product7.png')}}" alt="categories" class="img-fluid">
                    <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                    <div class="title-ctg">Clothing</div>
                    <ul class="info-link">
                        <li><a href="img/product/product7.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                        <li><a href="single-product-layout1.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                    </ul>
                    <div class="symbol-featured"><img src="{{asset('public/frontend/img/banner/symbol-featured.png"')}} alt=" symbol" class="img-fluid"> </div>
                </div>
            </div>
            <div class="item-content">
                <div class="title-ctg">Clothing</div>
                <h3 class="short-title"><a href="single-product1.html">V-Neck T-Shirt</a></h3>
                <h3 class="long-title"><a href="single-product1.html">Men's Threadborne Streaker V-Neck T-Shirt</a></h3>
                <ul class="upload-info">
                    <li class="date"><i class="fa fa-clock-o" aria-hidden="true"></i>07 Mar, 2017</li>
                    <li class="place"><i class="fa fa-map-marker" aria-hidden="true"></i>Sydney, Australia</li>
                    <li class="tag-ctg"><i class="fa fa-tag" aria-hidden="true"></i>Clothing</li>
                </ul>
                <p>Eimply dummy text of the printing and typesetting industrym has been the industry's standard dummy.</p>
                <div class="price">$15</div>
                <a href="single-product-layout1.html" class="product-details-btn">Details</a>
            </div>
        </div>
    </div> --}}
    </div>
    </div>
    </div>
    <div class="container">
        <div class="text-center item-mt">
            <h2 class="title-bold-dark mb-none">Do you have Something to Sell?</h2>
            <p>Post your ad on classipost.com</p>
            <a href="#" class="cp-default-btn direction-img">Post Your Ad Now!</a>
        </div>
    </div>
</section>
<!-- Products Area End Here -->
<!-- Counter Area Start Here -->
<section class="overlay-default s-space-equal overflow-hidden" style="background-image: url('img/banner/counter-back1.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-12">
                <div class="d-md-flex justify-md-content-center counter-box text-center--md">
                    <div>
                        <img src="{{asset('public/frontend/img/banner/counter1.png')}}" alt="counter" class="img-fluid mb20-auto--md">
                    </div>
                    <div>
                        <div class="counter counter-title" data-num="100000">1,00,000</div>
                        <h3 class="title-regular-light">Our Products</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="d-md-flex justify-md-content-center counter-box text-center--md">
                    <div>
                        <img src="{{asset('public/frontend/img/banner/counter2.png')}}" alt="counter" class="img-fluid mb20-auto--md">
                    </div>
                    <div>
                        <div class="counter counter-title" data-num="500000">5,00,000</div>
                        <h3 class="title-regular-light">Our Happy Buyers</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="d-md-flex justify-md-content-center counter-box text-center--md">
                    <div>
                        <img src="{{asset('public/frontend/img/banner/counter3.png')}}" alt="counter" class="img-fluid mb20-auto--md">
                    </div>
                    <div>
                        <div class="counter counter-title" data-num="200000">2,00,000</div>
                        <h3 class="title-regular-light">Verified Users</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Counter Area End Here -->
<!-- Pricing Plan Area Start Here -->
<section class="bg-body s-space-default">
    <div class="container">
        <div class="section-title-dark">
            <h2>Start Earning From Things You Don’t Need Anymore</h2>
            <p>It’s very Simple to choose pricing &amp; Plan</p>
        </div>
        <div class="row d-md-flex">
            <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                <div class="pricing-box bg-box">
                    <div class="plan-title">Free Post</div>
                    <div class="price">
                        <span class="currency">$</span>0
                        <span class="duration">/ Per mo</span>
                    </div>
                    <h3 class="title-bold-dark size-xl">Always FREE Ad Posting</h3>
                    <p>Post as many ads as you like for 30 days without limitations and 100% FREE SUBMIT AD</p>
                    <a href="#" class="cp-default-btn-lg">Submit Ad</a>
                </div>
            </div>
            <div class="d-flex align-items-center col-lg-2 col-md-12 col-sm-12 col-12">
                <div class="other-option bg-primary">or</div>
            </div>
            <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                <div class="pricing-box bg-box">
                    <div class="plan-title">Premium Post</div>
                    <div class="price">
                        <span class="currency">$</span>19
                        <span class="duration">/ Per mo</span>
                    </div>
                    <h3 class="title-bold-dark size-xl">Featured Ad Posting</h3>
                    <p>Post as many ads as you like for 30 days without limitations and 100% FREE SUBMIT AD</p>
                    <a href="#" class="cp-default-btn-lg">Submit Ad</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Pricing Plan Area End Here -->
<!-- Selling Process Area Start Here -->
<section class="bg-accent s-space-regular">
    <div class="container">
        <div class="section-title-dark">
            <h2>How To Start Selling Your Products</h2>
            <p>It’s very simple to choose pricing &amp; level of exposure on pricing page</p>
        </div>
        <ul class="process-area">
            <li>
                <img src="{{asset('public/frontend/img/banner/process1.png')}}" alt="process" class="img-fluid">
                <h3>Upload Your Products</h3>
                <p> Bmply dummy text of the printing and typesing industrypsum been the induse.</p>
            </li>
            <li>
                <img src="{{asset('public/frontend/img/banner/process2.png')}}" alt="process" class="img-fluid">
                <h3>Set Your Price</h3>
                <p> Bmply dummy text of the printing and typesing industrypsum been the induse.</p>
            </li>
            <li>
                <img src="{{asset('public/frontend/img/banner/process3.png')}}" alt="process" class="img-fluid">
                <h3>Start Your Business</h3>
                <p> Bmply dummy text of the printing and typesing industrypsum been the induse.</p>
            </li>
        </ul>
    </div>
</section>
<!-- Selling Process Area End Here -->
<!-- Subscribe Area Start Here -->
<section class="bg-body s-space full-width-border-top">
    <div class="container">
        <div class="section-title-dark">
            <h2 class="size-sm">Receive The Best Offers</h2>
            <p>Stay in touch with Classified Ads Wordpress Theme and we'll notify you about best ads</p>
        </div>
        <div class="input-group subscribe-area">
            <input type="text" placeholder="Type your e-mail address" class="form-control">
            <span class="input-group-addon">
                <button type="submit" class="cp-default-btn-xl">Subscribe</button>
            </span>
        </div>
    </div>
</section>
<!-- Subscribe Area End Here -->
@endsection
