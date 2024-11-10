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
                <!--register area start-->
                <div class="col-lg-6 col-md-6">
                    <div class="account_form register">
                        <h2>Register</h2>
                        <form action="" method="POST">
                            @csrf
                            <p>
                                <label>Name <span>*</span></label>
                                <input type="text" name="name" required>
                            </p>
                            <p>
                                <label>Email address <span>*</span></label>
                                <input type="text" name="email" required>
                            </p>
                            <p>
                                <label>Passwords <span>*</span></label>
                                <input type="password" name="password" required>
                            </p>
                            <p>
                                <label>Confirm Passwords <span>*</span></label>
                                <input type="password" name="confirm_password" required>
                            </p>
                            <div class="login_submit">
                                
                                <button type="submit">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--register area end-->
            </div>
        </div>
    </div>
@endsection