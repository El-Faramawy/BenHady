@extends('layouts.admin.app')
@section('page_title') الاعدادات @endsection

<link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الاعدادات</h3>
                </div>
                <div class="card-body">
                    <form  action="{{route('settings.update',$setting->id)}}" class="my_form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mt-0 mb-3">
                            <x-form.input class="col-md-6 col-sm-12" title="اسم الموقع" :required="true">
                                <input type="text" class="form-control form-control-solid" placeholder="اسم الموقع " name="name" value="{{$setting->name}}"/>
                            </x-form.input>
                            <x-form.input class="col-md-6 col-sm-12" title="نسبة خصم الثنايات" :required="true">
                                <input type="text" class="form-control form-control-solid numbersOnly" placeholder="نسبة خصم الثنايات " name="duet_discount" value="{{$setting->duet_discount}}"/>
                            </x-form.input>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <x-form.file name="اللوجو">
                                    <input type="file" class="dropify" name="logo" data-default-file="{{get_file($setting->logo)}}" data-height="250" />
                                </x-form.file>
                            </div><!-- COL END -->
                            <div class="col-lg-6 col-sm-12">
                                <x-form.file name="صورة المتصفح">
                                    <input type="file" class="dropify" name="fav_icon" data-default-file="{{get_file($setting->fav_icon)}}" data-height="250" />
                                </x-form.file>
                            </div><!-- COL END -->
                        </div>

                        <!-- ROW-2 CLOSED -->
                        <div class="card-footer ">
                            <input type="submit" class="btn btn-success mt-1" value="حفظ">
                            <input type="reset" class="btn btn-danger mt-1" value="الغاء">
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection
@push('admin_js')

    @include('layouts.admin.inc.my-form')

    <!-- INTERNAL   WYSIWYG Editor JS -->
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>

@endpush
