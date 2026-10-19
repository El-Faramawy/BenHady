@extends('layouts.admin.app')
@section('page_title') الإعدادات @endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <h3 class="card-title text-white mb-0"><i class="fe fe-settings ml-2"></i>إعدادات النظام</h3>
                </div>
                <div class="card-body">
                    <form id="settingsForm" action="{{ route('settings.update', $setting->id) }}" class="setting_form my_form" method="post" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Nav tabs styling & container -->
                        <style>
                            .settings-nav-tabs {
                                display: flex !important;
                                flex-direction: row !important;
                                gap: 12px;
                                background: #f1f5f9;
                                padding: 8px;
                                border-radius: 12px;
                                border: 1px solid #e2e8f0;
                                margin-bottom: 25px;
                                list-style: none;
                            }
                            .settings-nav-tabs .nav-item {
                                flex: 1 1 0;
                                margin: 0;
                            }
                            .settings-nav-tabs .nav-link {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                background: #ffffff;
                                color: #475569;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px !important;
                                padding: 13px 20px;
                                font-weight: 700;
                                font-size: 15px;
                                transition: all 0.25s ease-in-out;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
                                text-decoration: none;
                            }
                            .settings-nav-tabs .nav-link:hover {
                                background: #eef2ff;
                                color: #525ce5;
                                border-color: #c7d2fe;
                            }
                            .settings-nav-tabs .nav-link.active {
                                background: #525ce5 !important;
                                color: #ffffff !important;
                                border-color: #525ce5 !important;
                                box-shadow: 0 4px 12px rgba(82, 92, 229, 0.35) !important;
                            }
                            .settings-nav-tabs .nav-link.active i {
                                color: #ffffff !important;
                            }
                        </style>

                        <!-- Nav tabs -->
                        <ul class="nav settings-nav-tabs" id="settingTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                    <i class="fe fe-sliders ml-2"></i>الإعدادات العامة
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pages-tab" data-toggle="tab" href="#pages" role="tab" aria-controls="pages" aria-selected="false">
                                    <i class="fe fe-file-text ml-2"></i>باقي الإعدادات (الصفحات والسياسات)
                                </a>
                            </li>
                        </ul>

                        <!-- Tab contents -->
                        <div class="tab-content" id="settingTabContent">
                            <!-- Tab 1: General Settings -->
                            <div class="tab-pane fade show active p-2" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="row">
                                    <x-form.input class="col-md-12 col-sm-12" title="اسم الموقع" :required="true">
                                        <input type="text" class="form-control form-control-solid" placeholder="اسم الموقع" name="name" value="{{ $setting->name }}" required/>
                                    </x-form.input>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6 col-sm-12 mb-3">
                                        <x-form.file name="الشعار (اللوجو)">
                                            <input type="file" class="dropify" name="logo" data-default-file="{{ get_file($setting->logo) }}" data-height="200" />
                                        </x-form.file>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 mb-3">
                                        <x-form.file name="صورة المتصفح (Favicon)">
                                            <input type="file" class="dropify" name="fav_icon" data-default-file="{{ get_file($setting->fav_icon) }}" data-height="200" />
                                        </x-form.file>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Remaining Settings (Pages and Policies) -->
                            <div class="tab-pane fade p-2" id="pages" role="tabpanel" aria-labelledby="pages-tab">
                                <div class="card card-body bg-light mb-4">
                                    <h5 class="text-primary font-weight-bold mb-3"><i class="fe fe-info ml-2"></i>من نحن (About Us)</h5>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">من نحن (بالعربية)</label>
                                            <textarea class="form-control" name="about_us_ar" rows="4" placeholder="اكتب نبذة عن الموقع بالعربية...">{{ $setting->about_us_ar }}</textarea>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">من نحن (بالإنجليزية)</label>
                                            <textarea class="form-control" dir="ltr" name="about_us_en" rows="4" placeholder="About us in English...">{{ $setting->about_us_en }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-body bg-light mb-4">
                                    <h5 class="text-primary font-weight-bold mb-3"><i class="fe fe-check-circle ml-2"></i>الشروط والأحكام (Terms & Conditions)</h5>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">الشروط والأحكام (بالعربية)</label>
                                            <textarea class="form-control" name="terms_conditions_ar" rows="4" placeholder="الشروط والأحكام بالعربية...">{{ $setting->terms_conditions_ar }}</textarea>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">الشروط والأحكام (بالإنجليزية)</label>
                                            <textarea class="form-control" dir="ltr" name="terms_conditions_en" rows="4" placeholder="Terms & conditions in English...">{{ $setting->terms_conditions_en }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-body bg-light mb-3">
                                    <h5 class="text-primary font-weight-bold mb-3"><i class="fe fe-lock ml-2"></i>سياسة الخصوصية (Privacy Policy)</h5>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">سياسة الخصوصية (بالعربية)</label>
                                            <textarea class="form-control" name="privacy_policy_ar" rows="4" placeholder="سياسة الخصوصية بالعربية...">{{ $setting->privacy_policy_ar }}</textarea>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label class="font-weight-bold">سياسة الخصوصية (بالإنجليزية)</label>
                                            <textarea class="form-control" dir="ltr" name="privacy_policy_en" rows="4" placeholder="Privacy policy in English...">{{ $setting->privacy_policy_en }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer mt-3 bg-white px-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold ml-2 save_setting_btn">
                                <i class="fe fe-save ml-1"></i>حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin_js')
    @include('layouts.admin.inc.my-form')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>
@endpush
