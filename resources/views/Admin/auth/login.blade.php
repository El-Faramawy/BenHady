@extends('layouts.admin.auth.layout')
@section('content')
    <div class="col col-login mx-auto">
        <div class="text-center">
            <img src="{{get_file(setting()->logo)}}" class="header-brand-img" alt="">
        </div>
    </div>
    <!-- CONTAINER OPEN -->
    <div class="container-login100">
        <div class="wrap-login100 p-6">
            <form class="login100-form validate-form my_form" action="{{route('admin.post_login')}}"
                  enctype="application/x-www-form-urlencoded" method="post">
                @csrf
                <span class="login100-form-title">تسجيل الدخول</span>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" placeholder="البريد الالكترونى" name="email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope-o"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100 password" type="password" placeholder="كلمة السر" name="password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock"></i>
                    </span>
                </div>
                <div class="container-login100-form-btn">
                    <button type="submit" href="#" class="login100-form-btn btn-primary">
                        دخول
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
@endsection
