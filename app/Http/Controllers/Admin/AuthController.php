<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;

class AuthController extends Controller
{
    public function index(){
        if (admin()->check()){
            return redirect('admin/home');
        }
        return view('Admin/auth/login');
    }

    public function login(LoginRequest $request){
        if (admin()->attempt(['email' => request('email'), 'password' => request('password')])) {
            return response()->json(['message'=>'تم تسجيل الدخول بنجاح' , 'url' => route('admin.home')]);
        } else {
            return response()->json(['errors'=>['يوجد خطأ فى كلمة المرور']],500);
        }
    }

    public function logout(){
        admin()->logout();
        my_toaster('تم تسجيل الخروج بنجاح','warning');
        return redirect('admin/login');
    }
}
