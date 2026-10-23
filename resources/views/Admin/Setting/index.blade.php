@extends('layouts.admin.app')
@section('page_title') الإعدادات @endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <x-list.card>
        <x-list.card-header name="إعدادات النظام" :add_button="false" :delete_button="false"></x-list.card-header>
        <div class="card-body">
            <form id="settingsForm" action="{{ route('settings.update', $setting->id) }}" class="setting_form my_form" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

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
                        <div class="card card-body mb-4 shadow-sm" style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0" style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-info font-size-16"></i>
                                </div>
                                من نحن (About Us)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="من نحن (بالعربية)" :required="false">
                                    <textarea class="form-control" name="about_us_ar" rows="4" placeholder="اكتب نبذة عن الموقع بالعربية...">{{ $setting->about_us_ar }}</textarea>
                                </x-form.input>
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="من نحن (بالإنجليزية)" :required="false">
                                    <textarea class="form-control" dir="ltr" name="about_us_en" rows="4" placeholder="About us in English...">{{ $setting->about_us_en }}</textarea>
                                </x-form.input>
                            </div>
                        </div>

                        <div class="card card-body mb-4 shadow-sm" style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0" style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-check-circle font-size-16"></i>
                                </div>
                                الشروط والأحكام (Terms & Conditions)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="الشروط والأحكام (بالعربية)" :required="false">
                                    <textarea class="form-control" name="terms_conditions_ar" rows="4" placeholder="الشروط والأحكام بالعربية...">{{ $setting->terms_conditions_ar }}</textarea>
                                </x-form.input>
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="الشروط والأحكام (بالإنجليزية)" :required="false">
                                    <textarea class="form-control" dir="ltr" name="terms_conditions_en" rows="4" placeholder="Terms & conditions in English...">{{ $setting->terms_conditions_en }}</textarea>
                                </x-form.input>
                            </div>
                        </div>

                        <div class="card card-body mb-3 shadow-sm" style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0" style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-lock font-size-16"></i>
                                </div>
                                سياسة الخصوصية (Privacy Policy)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="سياسة الخصوصية (بالعربية)" :required="false">
                                    <textarea class="form-control" name="privacy_policy_ar" rows="4" placeholder="سياسة الخصوصية بالعربية...">{{ $setting->privacy_policy_ar }}</textarea>
                                </x-form.input>
                                <x-form.input class="col-md-6 col-sm-12 mb-3" title="سياسة الخصوصية (بالإنجليزية)" :required="false">
                                    <textarea class="form-control" dir="ltr" name="privacy_policy_en" rows="4" placeholder="Privacy policy in English...">{{ $setting->privacy_policy_en }}</textarea>
                                </x-form.input>
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
    </x-list.card>
@endsection

@push('admin_js')
    @include('layouts.admin.inc.my-form')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>
@endpush
