@extends('layouts.admin.app')
@section('page_title') البروفايل @endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل البروفايل</h3>
                </div>
                <div class="card-body">
                    <form  action="{{route('admin_profile.update')}}" class="my_form" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="exampleInputname">الاسم</label>
                                <input name="name" type="text" value="{{admin()->user()->name}}"  class="form-control" id="exampleInputname" placeholder="الاسم">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">البريد الالكترونى</label>
                        <input name="email" type="text" value="{{admin()->user()->email}}" class="form-control" id="exampleInputEmail1" placeholder="البريد الالكترونى">
                    </div>

                    <div class="form-group">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" placeholder="*******">
                    </div>
                    <div class="form-group">
                        <label class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="*******">
                    </div>
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
@endpush
