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
                      <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                           width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path
                              d="M20 8l-8 5-8-5v10h16zm0-2H4l8 4.99z" opacity=".3"/><path
                              d="M4 20h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2zM20 6l-8 4.99L4 6h16zM4 8l8 5 8-5v10H4V8z"/>
                      </svg>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100 password" type="password" placeholder="كلمة السر" name="password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                                 width="24"><g fill="none"><path d="M0 0h24v24H0V0z"/><path
                                        d="M0 0h24v24H0V0z" opacity=".87"/></g><path
                                    d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"
                                    opacity=".3"/><path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
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
