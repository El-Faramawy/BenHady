<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting()
    {
        return Setting::first();
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin');
    }
}

if (!function_exists('user_api')) {
    function user_api()
    {
        return auth()->guard('user_api');
    }
}

if (!function_exists('get_file')) {
    function get_file($file)
    {
        if (!is_null($file))
            return asset($file);
        else
            return asset('Admin/imgs/default.jpg');
    }
}

if (!function_exists('my_toaster')) {
    function my_toaster($message = 'تم بنجاح', $alert_type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('type', $alert_type);
    }
}

if (!function_exists('tableCheckbox')) {
    function tableCheckbox($id): string
    {
        return '<input type="checkbox" class="sub_chk" data-id="' . $id . '">';
    }
}

if (!function_exists('tableAction')) {
    function tableAction($id, $edit, $delete, $view = false)
    {
        $action = '';
        if ($edit) {
            $action .= '<button  id="editBtn" class="btn btn-default btn-primary btn-sm mb-2  mb-xl-0 " data-toggle="tooltip" title="تعديل"
                                data-id="' . $id . '" ><i class="fa fa-edit text-white"></i>
                            </button>';
        }
        if ($delete) {
            $action .= '<button class="btn btn-default btn-danger btn-sm mb-2 mb-xl-0 delete" data-toggle="tooltip" title="حذف"
                                data-id="' . $id . '" ><i class="fa fa-trash-o text-white"></i>
                            </button>';

        }
        if ($view) {
            $action .= '<button class="btn btn-default btn-success btn-sm mb-2 mb-xl-0 view" data-toggle="tooltip" title="عرض"
                                data-id="' . $id . '" ><i class="fa fa-eye text-white"></i>
                            </button>';

        }
        return $action;
    }
}
