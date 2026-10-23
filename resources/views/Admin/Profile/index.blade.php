@extends('layouts.admin.app')
@section('page_title') البروفايل @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="تعديل البروفايل" :add_button="false" :delete_button="false" />
        <div class="card-body">
            <form action="{{ route('admin_profile.update') }}" class="my_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <x-form.input class="col-md-6 col-sm-12" title="الاسم" :required="true">
                        <input name="name" type="text" value="{{ admin()->user()->name }}" class="form-control" id="exampleInputname" placeholder="الاسم" required>
                    </x-form.input>

                    <x-form.input class="col-md-6 col-sm-12" title="البريد الإلكتروني" :required="true">
                        <input name="email" type="email" value="{{ admin()->user()->email }}" class="form-control" id="exampleInputEmail1" placeholder="البريد الإلكتروني" required>
                    </x-form.input>

                    <x-form.input class="col-md-6 col-sm-12" title="كلمة المرور (اتركها فارغة إذا لم ترغب بالتغيير)" :required="false">
                        <input type="password" name="password" class="form-control" placeholder="*******">
                    </x-form.input>

                    <x-form.input class="col-md-6 col-sm-12" title="تأكيد كلمة المرور" :required="false">
                        <input type="password" name="confirm_password" class="form-control" placeholder="*******">
                    </x-form.input>
                </div>

                <div class="card-footer bg-white px-0 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary px-5 font-weight-bold ml-2">
                        <i class="fe fe-save ml-1"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </x-list.card>
@endsection

@push('admin_js')
    @include('layouts.admin.inc.my-form')
@endpush
