@extends('frontend.layouts.master')
@section('content')
<!-- Product Area Start Here -->
<section class="s-space-bottom-full bg-accent-shadow-body">
    <div class="container">
        <div class="breadcrumbs-area">
            <ul>
                <li><a href="#">Home</a> -</li>
                <li><a href="#">Electronics</a> -</li>
                <li class="active">Computer</li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="gradient-wrapper item-mb">
                    <div class="gradient-title">
                        <h2>Black short dress with wings in the back</h2>
                    </div>
                    <div class="gradient-padding reduce-padding">
                        <div class="single-product-img-layout1 item-mb">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="tab-content">
                                        <span class="price">$1,550</span>
                                        <div role="tabpanel" class="tab-pane fade active show" id="related1">
                                            <a href="#" class="zoom ex1"><img alt="single" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="related2">
                                            <a href="#" class="zoom ex1"><img alt="single" src="img/product/single-product2.jpg" class="img-fluid"></a>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="related3">
                                            <a href="#" class="zoom ex1"><img alt="single" src="img/product/single-product3.jpg" class="img-fluid"></a>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="related4">
                                            <a href="#" class="zoom ex1"><img alt="single" src="img/product/single-product4.jpg" class="img-fluid"></a>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="related5">
                                            <a href="#" class="zoom ex1"><img alt="single" src="img/product/single-product5.jpg" class="img-fluid"></a>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="related6">
                                            <a href="#" class="zoom ex1"><img alt="single" src="img/product/single-product6.jpg" class="img-fluid"></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">                                            
                                    <ul class="nav tab-nav tab-nav-inline cp-carousel nav-control-middle" data-loop="true" data-items="6" data-margin="15" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="2" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="4" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-Large="6" data-r-Large-nav="true" data-r-Large-dots="false">
                                        <li class="nav-item">
                                            <a class="active" href="#related1" data-toggle="tab" aria-expanded="false"><img alt="related1" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#related2" data-toggle="tab" aria-expanded="false"><img alt="related2" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#related3" data-toggle="tab" aria-expanded="false"><img alt="related3" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#related4" data-toggle="tab" aria-expanded="false"><img alt="related4" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#related5" data-toggle="tab" aria-expanded="false"><img alt="related5" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#related6" data-toggle="tab" aria-expanded="false"><img alt="related6" src="{{asset('storage/app/public/product_image/'. $item->product_image)}}" class="img-fluid"></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="section-title-left-dark child-size-xl title-bar item-mb">
                            <h3>Product Details:</h3>
                            <p>Powerful dual-core and quad-core Intel processors, more advanced graphics, faster PCIe-based flash storage, superfast memory, and Thunderbolt 2, MacBook Pro with Retina display delivers all the performance you want from a notebook. memory, and Thunderbolt 2, MacBook Pro with Ret aew.
                            </p>
                        </div>
                        <div class="section-title-left-dark child-size-xl">
                            <h3>Specification:</h3>
                        </div>
                        <ul class="specification-layout1 mb-40">
                            <li>256GB PCIe flash storage</li>
                            <li>2.7 GHz dual-core Intel Core i5 processor</li>
                            <li>Turbo Boost up to 3.1GHz</li>
                            <li>Intel Iris Graphics 6100</li>
                            <li>8GB memory (up from 4GB in 2013 model)</li>
                            <li>10 hour battery life</li>
                            <li>13.3" Retina Display</li>
                            <li>Intect Box</li>
                            <li>1 Year international warranty</li>
                        </ul>
                        <ul class="item-actions border-top">
                            <li><a href="#"><i class="fa fa-heart" aria-hidden="true"></i>Save Ad</a></li>
                            <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i>Share ad</a></li>
                            <li class="item-danger"><a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>Report abuse</a></li>
                        </ul>
                    </div>
                </div>
                <div class="gradient-wrapper item-mb">
                    <div class="gradient-title">
                        <h2>More Ads From This User </h2>
                    </div>
                    <div class="gradient-padding">
                        <div class="cp-carousel nav-control-middle category-grid-layout1" data-loop="true" data-items="4" data-margin="30" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="2" data-r-medium-nav="true" data-r-medium-dots="false" data-r-Large="3" data-r-Large-nav="true" data-r-Large-dots="false">
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product1.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign active" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Clothing</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product1.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout1.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <div class="title-ctg">Clothing</div>
                                    <h3 class="short-title"><a href="single-product1.html">Cotton T-Shirt</a></h3>
                                    <h3 class="long-title"><a href="single-product1.html">Men's Basic Cotton T-Shirt</a></h3>
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
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product2.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign active" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Electronics</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product2.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout2.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
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
                                    <a href="single-product-layout1.html" class="product-details-btn">Details</a>
                                </div>
                            </div>
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product3.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign active" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Electronics</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product3.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout3.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
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
                                    <a href="single-product-layout1.html" class="product-details-btn">Details</a>
                                </div>
                            </div>
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product4.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Clothing</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product4.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout1.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
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
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product5.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Clothing</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product5.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout2.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
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
                                    <a href="single-product-layout1.html" class="product-details-btn">Details</a>
                                </div>
                            </div>
                            <div class="product-box">
                                <div class="item-mask-wrapper">
                                    <div class="item-mask secondary-bg-box"><img src="img/product/product6.png" alt="categories" class="img-fluid">
                                        <div class="trending-sign" data-tips="Featured"> <i class="fa fa-bolt" aria-hidden="true"></i> </div>
                                        <div class="title-ctg">Clothing</div>
                                        <ul class="info-link">
                                            <li><a href="img/product/product6.png" class="elv-zoom" data-fancybox-group="gallery" title="Title Here"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
                                            <li><a href="single-product-layout3.html"><i class="fa fa-link" aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="symbol-featured"><img src="img/banner/symbol-featured.png" alt="symbol" class="img-fluid"> </div>
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
                                    <a href="single-product-layout1.html" class="product-details-btn">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="add-layout2-left d-flex align-items-center">
                            <div>
                                <h2>Do you Have Something To Sell?</h2>
                                <h3>Post your ad on classipost.com</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                        <div class="add-layout2-right d-flex align-items-center justify-content-end mb--sm">
                            <a href="#" class="cp-default-btn-sm-primary">Post Your Ad Now!</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="sidebar-item-box">
                    <div class="gradient-wrapper">
                        <div class="gradient-title">
                            <h3>Seller Information</h3>
                        </div>
                        <ul class="sidebar-seller-information">
                            <li>
                                <div class="media">
                                    <img src="img/user/user1.png" alt="user" class="img-fluid pull-left">
                                    <div class="media-body">
                                        <span>Posted By</span>
                                        <h4>Mr. Fahim Rahman</h4>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <img src="img/user/user2.png" alt="user" class="img-fluid pull-left">
                                    <div class="media-body">
                                        <span>Location</span>
                                        <h4>Gulshan, Dhaka</h4>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <img src="img/user/user3.png" alt="user" class="img-fluid pull-left">
                                    <div class="media-body">
                                        <span>Contact Number</span>
                                        <h4>01612854530</h4>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <img src="img/user/user4.png" alt="user" class="img-fluid pull-left">
                                    <div class="media-body">
                                        <span>Want To Live Chat</span>
                                        <h4>Chat Now!</h4>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <img src="img/user/user5.png" alt="user" class="img-fluid pull-left">
                                    <div class="media-body">
                                        <span>User Type</span>
                                        <h4>Verified</h4>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-item-box">
                    <div class="gradient-wrapper">
                        <div class="gradient-title">
                            <h3>Item Details</h3>
                        </div>
                        <ul class="sidebar-item-details">
                            <li>Condition:<span>New</span></li>
                            <li>Brand:<span>Apple</span></li>
                            <li>Color:<span>White</span></li>
                            <li>Warranty:<span>1 Year</span></li>
                            <li>
                                <ul class="sidebar-social">
                                    <li>Share:</li>
                                    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-item-box">
                    <div class="gradient-wrapper">
                        <div class="gradient-title">
                            <h3>Safety Tips for Buyers</h3>
                        </div>
                        <ul class="sidebar-safety-tips">
                            <li>Meet seller at a public place</li>
                            <li>Check The item before you buy</li>
                            <li>Pay only after collecting The item</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Area End Here -->
@endsection