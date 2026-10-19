@extends('layouts.admin.app')
@section('page_title') سياسات التأجير والإلغاء @endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <h3 class="card-title text-white mb-0"><i class="fe fe-shield ml-2"></i>سياسات التأجير، التأمين والإلغاء</h3>
                </div>
                <div class="card-body">
                    <form id="policyForm" action="{{ route('rental_policies.update', $policy->id) }}" class="policy_form my_form" method="post" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Nav tabs styling & container -->
                        <style>
                            .policy-nav-tabs {
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
                            .policy-nav-tabs .nav-item {
                                flex: 1 1 0;
                                margin: 0;
                            }
                            .policy-nav-tabs .nav-link {
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
                            .policy-nav-tabs .nav-link:hover {
                                background: #eef2ff;
                                color: #525ce5;
                                border-color: #c7d2fe;
                            }
                            .policy-nav-tabs .nav-link.active {
                                background: #525ce5 !important;
                                color: #ffffff !important;
                                border-color: #525ce5 !important;
                                box-shadow: 0 4px 12px rgba(82, 92, 229, 0.35) !important;
                            }
                            .policy-nav-tabs .nav-link.active i {
                                color: #ffffff !important;
                            }
                        </style>

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
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fe fe-info ml-2 font-size-18"></i>
                                    <span>هذه الشروط تظهر للمستخدمين عند حجز وإلغاء السيارات في التطبيق.</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">سياسة الإلغاء والتعديل (بالعربية) <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="cancellation_policy_ar" rows="8" placeholder="اكتب شروط وسياسة الإلغاء بالعربية..." required>{{ $policy->cancellation_policy_ar }}</textarea>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">سياسة الإلغاء والتعديل (بالإنجليزية)</label>
                                        <textarea class="form-control" dir="ltr" name="cancellation_policy_en" rows="8" placeholder="Cancellation policy in English...">{{ $policy->cancellation_policy_en }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Insurance Policy -->
                            <div class="tab-pane fade p-2" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fe fe-info ml-2 font-size-18"></i>
                                    <span>توضح وثيقة التأمين تفاصيل التغطية التأمينية ونسبة التحمل للعميل.</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">وثيقة التأمين (بالعربية) <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="insurance_policy_ar" rows="8" placeholder="اكتب تفاصيل وثيقة التأمين بالعربية..." required>{{ $policy->insurance_policy_ar }}</textarea>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">وثيقة التأمين (بالإنجليزية)</label>
                                        <textarea class="form-control" dir="ltr" name="insurance_policy_en" rows="8" placeholder="Insurance policy in English...">{{ $policy->insurance_policy_en }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Tenant Instructions -->
                            <div class="tab-pane fade p-2" id="instructions" role="tabpanel" aria-labelledby="instructions-tab">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fe fe-info ml-2 font-size-18"></i>
                                    <span>تعليمات وإرشادات استلام وتسليم السيارة الخاصة بالمستأجر.</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">تعليمات المستأجر (بالعربية) <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="tenant_instructions_ar" rows="8" placeholder="اكتب التعليمات والنقاط المطلوبة من المستأجر بالعربية..." required>{{ $policy->tenant_instructions_ar }}</textarea>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label class="font-weight-bold">تعليمات المستأجر (بالإنجليزية)</label>
                                        <textarea class="form-control" dir="ltr" name="tenant_instructions_en" rows="8" placeholder="Tenant instructions in English...">{{ $policy->tenant_instructions_en }}</textarea>
                                    </div>
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
            </div>
        </div>
    </div>
@endsection

@push('admin_js')
    @include('layouts.admin.inc.my-form')
@endpush
