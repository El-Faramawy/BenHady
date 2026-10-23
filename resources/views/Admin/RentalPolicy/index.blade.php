@extends('layouts.admin.app')
@section('page_title') سياسات التأجير والإلغاء @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="سياسات التأجير، التأمين والإلغاء" :add_button="false" :delete_button="false" />
        <div class="card-body">
            <form id="policyForm" action="{{ route('rental_policies.update', $policy->id) }}" class="policy_form my_form" method="post" novalidate>
                @csrf
                @method('PUT')

                <!-- Nav tabs -->
                <ul class="nav policy-nav-tabs" id="policyTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="cancellation-tab" data-toggle="tab" href="#cancellation" role="tab" aria-controls="cancellation" aria-selected="true">
                            <i class="fe fe-x-circle ml-2"></i>سياسة الإلغاء والتعديل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="insurance-tab" data-toggle="tab" href="#insurance" role="tab" aria-controls="insurance" aria-selected="false">
                            <i class="fe fe-shield ml-2"></i>وثيقة التأمين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="instructions-tab" data-toggle="tab" href="#instructions" role="tab" aria-controls="instructions" aria-selected="false">
                            <i class="fe fe-file-text ml-2"></i>تعليمات المستأجر
                        </a>
                    </li>
                </ul>

                <!-- Tab contents -->
                <div class="tab-content" id="policyTabContent">
                    <!-- Tab 1: Cancellation Policy -->
                    <div class="tab-pane fade show active p-2" id="cancellation" role="tabpanel" aria-labelledby="cancellation-tab">
                        <div class="d-flex align-items-center p-3 mb-4 rounded shadow-sm" style="background: #F5FDEA; border: 1px solid #D8F2B5; gap: 12px;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 36px; height: 36px; background: #E4F7CA; color: #2E5A1C;">
                                <i class="fe fe-info font-size-18"></i>
                            </div>
                            <div style="color: #1E3A1A; font-size: 13.5px; font-weight: 600;">
                                هذه الشروط تظهر للمستخدمين عند حجز وإلغاء السيارات في التطبيق.
                            </div>
                        </div>
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="سياسة الإلغاء والتعديل (بالعربية)" :required="true">
                                <textarea class="form-control" name="cancellation_policy_ar" rows="8" placeholder="اكتب شروط وسياسة الإلغاء بالعربية..." required>{{ $policy->cancellation_policy_ar }}</textarea>
                            </x-form.input>
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="سياسة الإلغاء والتعديل (بالإنجليزية)" :required="false">
                                <textarea class="form-control" dir="ltr" name="cancellation_policy_en" rows="8" placeholder="Cancellation policy in English...">{{ $policy->cancellation_policy_en }}</textarea>
                            </x-form.input>
                        </div>
                    </div>

                    <!-- Tab 2: Insurance Policy -->
                    <div class="tab-pane fade p-2" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                        <div class="d-flex align-items-center p-3 mb-4 rounded shadow-sm" style="background: #F5FDEA; border: 1px solid #D8F2B5; gap: 12px;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 36px; height: 36px; background: #E4F7CA; color: #2E5A1C;">
                                <i class="fe fe-shield font-size-18"></i>
                            </div>
                            <div style="color: #1E3A1A; font-size: 13.5px; font-weight: 600;">
                                توضح وثيقة التأمين تفاصيل التغطية التأمينية ونسبة التحمل للعميل في التطبيق.
                            </div>
                        </div>
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="وثيقة التأمين (بالعربية)" :required="true">
                                <textarea class="form-control" name="insurance_policy_ar" rows="8" placeholder="اكتب تفاصيل وثيقة التأمين بالعربية..." required>{{ $policy->insurance_policy_ar }}</textarea>
                            </x-form.input>
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="وثيقة التأمين (بالإنجليزية)" :required="false">
                                <textarea class="form-control" dir="ltr" name="insurance_policy_en" rows="8" placeholder="Insurance policy in English...">{{ $policy->insurance_policy_en }}</textarea>
                            </x-form.input>
                        </div>
                    </div>

                    <!-- Tab 3: Tenant Instructions -->
                    <div class="tab-pane fade p-2" id="instructions" role="tabpanel" aria-labelledby="instructions-tab">
                        <div class="d-flex align-items-center p-3 mb-4 rounded shadow-sm" style="background: #F5FDEA; border: 1px solid #D8F2B5; gap: 12px;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 36px; height: 36px; background: #E4F7CA; color: #2E5A1C;">
                                <i class="fe fe-file-text font-size-18"></i>
                            </div>
                            <div style="color: #1E3A1A; font-size: 13.5px; font-weight: 600;">
                                تعليمات وإرشادات استلام وتسليم السيارة الخاصة بالمستأجر التي تظهر للمستخدمين في التطبيق.
                            </div>
                        </div>
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="تعليمات المستأجر (بالعربية)" :required="true">
                                <textarea class="form-control" name="tenant_instructions_ar" rows="8" placeholder="اكتب التعليمات والنقاط المطلوبة من المستأجر بالعربية..." required>{{ $policy->tenant_instructions_ar }}</textarea>
                            </x-form.input>
                            <x-form.input class="col-md-6 col-sm-12 mb-3" title="تعليمات المستأجر (بالإنجليزية)" :required="false">
                                <textarea class="form-control" dir="ltr" name="tenant_instructions_en" rows="8" placeholder="Tenant instructions in English...">{{ $policy->tenant_instructions_en }}</textarea>
                            </x-form.input>
                        </div>
                    </div>
                </div>

                <div class="card-footer mt-3 bg-white px-0 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 font-weight-bold ml-2 save_policy_btn">
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
