@extends('app')
@section('content')
    <!--shopping cart area start -->
    <div class="shopping_cart_area mt-32">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="#">
                <div class="row">
                    <div class="col-12">
                        <div class="table_desc">
                            <div class="cart_page table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_remove">Xóa</th>
                                            <th class="product_thumb">Ảnh</th>
                                            <th class="product_name">Tên sản phẩm</th>
                                            <th class="product-price">Giá</th>
                                            <th class="product_quantity">Số lượng</th>
                                            <th class="product_total">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $key)
                                        <tr>
                                            
                                            <td class="product_remove"><a href="#"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                            <td class="product_thumb"><a href="#"><img
                                                        src="{{ asset('storage/' . $key->image)}}" alt=""></a></td>
                                            <td class="product_name"><a href="#">{{ $key->product_name}}</a></td>
                                            <td class="product-price">{{ $key->price}}</td>
                                            <td class="product_quantity"><label>{{ $key->quantity}}</label> <input min="1"
                                                    max="100" value="1" type="number"></td>
                                            <td class="product_total">{{ $key->quantity * $key->price}}vnđ</td>


                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="cart_submit">
                                <button type="submit">update cart</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--coupon code area start-->
                <div class="coupon_area">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code left">
                                <h3>Coupon</h3>
                                <div class="coupon_inner">
                                    <p>Enter your coupon code if you have one.</p>
                                    <input placeholder="Coupon code" type="text">
                                    <button type="submit">Apply coupon</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code right">
                                <h3>Cart Totals</h3>
                                <div class="coupon_inner">
                                    <div class="cart_subtotal">
                                        <p>Tổng tiền</p>
                                        <p class="cart_amount">{{$totalAmount}}vnđ</p>
                                    </div>
                                    <div class="cart_subtotal ">
                                        <p>Phí vận chuyển</p>
                                        <p class="cart_amount"><span>Flat Rate:</span> £255.00</p>
                                    </div>
                                    <a href="#">Calculate shipping</a>

                                    <div class="cart_subtotal">
                                        <p>Total</p>
                                        <p class="cart_amount">£215.00</p>
                                    </div>
                                    <div class="checkout_btn">
                                        <a href="#">Proceed to Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--coupon code area end-->
            </form>
        </div>
    </div>
@endsection 