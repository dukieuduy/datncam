@extends('app')

@section('content')
    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
    <div class="product_details mt-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product-details-tab">

                        <div id="img-1" class="zoomWrapper single-zoom">
                            <a href="#">
                                {{-- <img id="zoom1" src="{{ asset('assets/img/product/product15.jpg') }}" data-zoom-image="assets/img/product/product15.jpg" alt="big-1"> --}}
                                <img id="zoom1" src="{{ asset('assets/img/product/product15.jpg') }}"
                                    data-zoom-image="{{ asset('assets/img/product/product15.jpg') }}" alt="big-1">
                                <img id="zoom1" src="{{ asset('assets/img/product/product15.jpg') }}"
                                    data-zoom-image="{{ asset('assets/img/product/product15.jpg') }}" alt="big-1">

                            </a>
                        </div>

                        <div class="single-zoom-thumb">
                            <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                @foreach ($variations as $item)
                                    <li>
                                        <a href="#" class="elevatezoom-gallery active" data-update=""
                                            data-image="{{ asset('storage/' . $item->image) }}"
                                            data-zoom-image="{{ asset('storage/' . $item->image) }}">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="zo-th-1" />
                                        </a>
                                @foreach ($variations as $item)
                                    <li>
                                        <a href="#" class="elevatezoom-gallery active" data-update=""
                                            data-image="{{ asset('storage/' . $item->image) }}"
                                            data-zoom-image="{{ asset('storage/' . $item->image) }}">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="zo-th-1" />
                                        </a>

                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-md-6">
                    <div class="product_d_right">
                        <form action="{{ route('cart.add') }}" method="POST">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <h1>{{ $product->name }}</h1>
                            <h1>{{ $product->name }}</h1>
                            <div class="product_nav">
                                <ul>
                                    <li class="prev"><a href="product-details.html"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li class="next"><a href="variable-product.html"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                    <li class="prev"><a href="product-details.html"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li class="next"><a href="variable-product.html"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class=" product_ratting">
                                <ul>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li class="review"><a href="#"> (customer review ) </a></li>
                                </ul>

                            </div>
                            <div class="price_box">
                                <span class="current_price">{{$variations[0]->price}}vnđ</span>
                                <span class="old_price">$80.00</span>

                            </div>
                            <div class="product_desc">
                                <p>{{ $product->description }} </p>
                                <p>{{ $product->description }} </p>
                            </div>
                            <div class="product_variant color">
                                <h3>Chọn màu</h3>
                                <ul>
                                    <!-- Checkbox để chọn màu biến thể -->
                                    @foreach ($colors as $value)
                                        {{-- <input type="checkbox" name="selectedColor[]" value="red" style="background-color: red; border: none;"> {{$value}} --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selectedColor"
                                                id="{{ $value }}" value="{{ $value }}">
                                            <label class="form-check-label color-option"
                                                for="colorRed">{{ $value }}</label>
                                        </div>
                                    @endforeach
                                    <!-- Checkbox để chọn màu biến thể -->
                                    @foreach ($colors as $value)
                                        {{-- <input type="checkbox" name="selectedColor[]" value="red" style="background-color: red; border: none;"> {{$value}} --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selectedColor"
                                                id="{{ $value }}" value="{{ $value }}">
                                            <label class="form-check-label color-option"
                                                for="colorRed">{{ $value }}</label>
                                        </div>
                                    @endforeach
                                </ul>
                                <div class="mb-4">
                                    <h3>Chọn kích cỡ</h3>
                                    @foreach ($sizes as $value)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selectedSize"
                                                id="{{ $value }}" value="{{ $value }}">
                                            <label class="form-check-label"
                                                for="{{ $value }}">{{ $value }}</label>
                                        </div>
                                    @foreach ($sizes as $value)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selectedSize"
                                                id="{{ $value }}" value="{{ $value }}">
                                            <label class="form-check-label"
                                                for="{{ $value }}">{{ $value }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product_variant quantity">
                                <label>Số Lượng</label>
                                <input name="quantity" min="1" max="100" value="1" type="number">
                                <input type="hidden" name="product_name" value="{{ $product->name }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">


                                <button class="button" type="submit">Thêm vào Giỏ Hàng</button>
                            </div>
                        </form>

                        <form action="{{ route('wishlist.create', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class=" product_d_action">
                                <ul>
                                    <li><button type="submit" title="Add to wishlist">+ Thêm vào danh mục yêu thích
                                        </button></li>
                                    <li><button title="Add to wishlist">+ Compare</button></li>
                                </ul>
                            </div>
                        </form>

                        <div class="product_meta">
                            <span>Danh mục: <a href="#">{{ $category->name }}</a></span>
                            <div style="margin-top: 15px">
                                <label>Số lượng sản phẩm trong kho {{ $stockQuantity }}</label>
                            </div>
                        </div>


                        <div class="priduct_social">
                            <ul>
                                <li><a class="facebook" href="#" title="facebook"><i class="fa fa-facebook"></i> Like</a></li>
                                <li><a class="twitter" href="#" title="twitter"><i class="fa fa-twitter"></i> tweet</a></li>
                                <li><a class="pinterest" href="#" title="pinterest"><i class="fa fa-pinterest"></i> save</a></li>
                                <li><a class="google-plus" href="#" title="google +"><i class="fa fa-google-plus"></i> share</a></li>
                                <li><a class="linkedin" href="#" title="linkedin"><i class="fa fa-linkedin"></i> linked</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
