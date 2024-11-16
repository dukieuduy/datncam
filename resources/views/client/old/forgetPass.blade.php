@extends('app')
@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="index.html">home</a></li>
                            <li>Login</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->


    <!-- customer login start -->
    <div class="customer_login mt-32">
        <div class="container">
            <div class="row">
                <!--login area start-->
                <div class="col-lg-6 col-md-6">
                    <div class="account_form">
                        <h2>Nhập email của bạn</h2>
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{{ $message }}</strong>
                            </div>

                            @endif
                        <form action="" method="POST">
                            @csrf
                            <p>
                                <label>Email <span>*</span></label>
                                <input type="text" name="email" >
                                @if ($errors->has('email'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            </p>
                            <div class="login_submit">
                                <a href="{{route('login')}}">Login</a>
                                <a href="{{route('register')}}" class="text-primary hover:text-danger" style="margin-left: 2%">Register</a>

                                <button type="submit">login</button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection