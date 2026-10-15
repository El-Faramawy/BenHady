<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Traits\PhotoTrait;

class SettingController extends Controller
{
    use PhotoTrait;

    public function index(){
        $setting = Setting::first();
        return view('Admin.Setting.index',compact('setting'));
    }

    public function update(Request $request , Setting $setting){
        $data = $request->all();
        if ( $request->logo && $request->logo != null ){
            $data['logo']    = $this->saveImage($request->logo,'uploads/setting',$setting->logo);
        }
        if ( $request->fav_icon && $request->fav_icon != null ){
            $data['fav_icon']    = $this->saveImage($request->fav_icon,'uploads/setting',$setting->fav_icon);
        }
        $setting->update($data);
        return response()->json(['message' => 'تم التعديل بنجاح']);
    }
}
