<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('cars.store') }}">
    @csrf

    <!-- Nav tabs -->
    <ul class="nav car-nav-tabs mb-4" id="carCreateTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-create-tab" data-toggle="tab" href="#general-create" role="tab">
                <i class="fa fa-info-circle ml-1"></i>البيانات الأساسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="specs-create-tab" data-toggle="tab" href="#specs-create" role="tab">
                <i class="fa fa-cogs ml-1"></i>المواصفات والفروع
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="features-create-tab" data-toggle="tab" href="#features-create" role="tab">
                <i class="fa fa-check-square-o ml-1"></i>المميزات والتجهيزات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="media-create-tab" data-toggle="tab" href="#media-create" role="tab">
                <i class="fa fa-picture-o ml-1"></i>الصور والوصف
            </a>
        </li>
    </ul>

    <!-- Tab contents -->
    <div class="tab-content" id="carCreateTabContent">
        <!-- Tab 1: General Info -->
        <div class="tab-pane fade show active" id="general-create" role="tabpanel">
            <div class="row">
                <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
                    <input type="text" class="form-control form-control-solid" placeholder="مثال: تويوتا كامري 2025" name="name_ar" required/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
                    <input type="text" class="form-control form-control-solid" placeholder="Example: Toyota Camry 2025" name="name_en" required/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الماركة / الشركة المصنعة" :required="true">
                    <select name="brand_id" class="form-control form-control-solid" required>
                        <option value="">اختر الماركة</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name_ar }} ({{ $brand->name_en }})</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="قسم / فئة السيارة" :required="true">
                    <select name="category_id" class="form-control form-control-solid" required>
                        <option value="">اختر القسم</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-4" title="سنة الصنع" :required="true">
                    <select name="model_year_id" class="form-control form-control-solid" required>
                        <option value="">اختر السنة</option>
                        @foreach($modelYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-4" title="السعر اليومي (ر.س)" :required="true">
                    <input type="number" step="0.01" min="0" class="form-control form-control-solid" placeholder="مثال: 250" name="daily_price" required/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="عدد المقاعد" :required="true">
                    <input type="number" min="1" max="50" class="form-control form-control-solid" placeholder="مثال: 5" name="seats" value="5" required/>
                </x-form.input>
            </div>
        </div>

        <!-- Tab 2: Specs & Branches -->
        <div class="tab-pane fade" id="specs-create" role="tabpanel">
            <div class="row">
                <x-form.input class="col-sm-6" title="نوع ناقل الحركة" :required="true">
                    <select name="transmission_id" class="form-control form-control-solid" required>
                        <option value="">اختر ناقل الحركة</option>
                        @foreach($transmissions as $transmission)
                            <option value="{{ $transmission->id }}">{{ $transmission->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="نوع الوقود" :required="true">
                    <select name="fuel_type_id" class="form-control form-control-solid" required>
                        <option value="">اختر نوع الوقود</option>
                        @foreach($fuelTypes as $fuelType)
                            <option value="{{ $fuelType->id }}">{{ $fuelType->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الفرع المتواجدة به السيارة حالياً" :required="true">
                    <select name="branch_id" class="form-control form-control-solid" required>
                        <option value="">اختر الفرع الرئيسي</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name_ar }} ({{ $branch->city?->name_ar }})</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="حالة السيارة الحالية" :required="true">
                    <select name="status" class="form-control form-control-solid" required>
                        <option value="available">متاحة للطلب (Available)</option>
                        <option value="rented">مؤجرة حالياً (Rented)</option>
                        <option value="maintenance">في الصيانة (Maintenance)</option>
                        <option value="reserved">محجوزة (Reserved)</option>
                    </select>
                </x-form.input>

                <div class="col-sm-12 mb-3">
                    <label class="font-weight-bold">الفروع الإضافية المتاح استلام السيارة منها (اختياري)</label>
                    <div class="row p-2 border rounded bg-light" style="max-height: 160px; overflow-y: auto;">
                        @foreach($branches as $branch)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <label class="branch-checkbox-card d-flex align-items-center justify-content-between">
                                    <span class="font-weight-bold font-size-13 text-dark">
                                        <i class="fa fa-map-marker ml-1 text-primary"></i>{{ $branch->name_ar }}
                                    </span>
                                    <div class="custom-control custom-checkbox m-0 p-0">
                                        <input type="checkbox" class="custom-control-input branch-input" name="branches[]" value="{{ $branch->id }}" id="br_create_{{ $branch->id }}">
                                        <label class="custom-control-label" for="br_create_{{ $branch->id }}"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-12">
                    <label class="font-weight-bold d-block mb-2">خيارات العرض والظهور</label>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-2">
                            <label class="toggle-switch-card d-flex align-items-center justify-content-between p-3 border rounded shadow-sm is-checked">
                                <div class="d-flex align-items-center">
                                    <div class="toggle-icon-wrap ml-2">
                                        <i class="fa fa-power-off text-success font-size-16"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold d-block text-dark font-size-13">تفعيل في التطبيق</span>
                                        <small class="text-muted">إظهار السيارة للمستخدمين</small>
                                    </div>
                                </div>
                                <div class="custom-switch-btn">
                                    <input type="checkbox" name="is_active" value="1" class="switch-btn-input" id="switch_is_active_create" checked>
                                    <label class="switch-btn-slider" for="switch_is_active_create"></label>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-2">
                            <label class="toggle-switch-card d-flex align-items-center justify-content-between p-3 border rounded shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="toggle-icon-wrap ml-2">
                                        <i class="fa fa-star text-warning font-size-16"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold d-block text-dark font-size-13">سيارة مميزة (Featured)</span>
                                        <small class="text-muted">تثبيت في قسم المميزة</small>
                                    </div>
                                </div>
                                <div class="custom-switch-btn">
                                    <input type="checkbox" name="is_featured" value="1" class="switch-btn-input" id="switch_is_featured_create">
                                    <label class="switch-btn-slider" for="switch_is_featured_create"></label>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-2">
                            <label class="toggle-switch-card d-flex align-items-center justify-content-between p-3 border rounded shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="toggle-icon-wrap ml-2">
                                        <i class="fa fa-thumbs-up text-primary font-size-16"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold d-block text-dark font-size-13">مختارة بعناية (Handpicked)</span>
                                        <small class="text-muted">ضمن الاختيارات الموصى بها</small>
                                    </div>
                                </div>
                                <div class="custom-switch-btn">
                                    <input type="checkbox" name="is_handpicked" value="1" class="switch-btn-input" id="switch_is_handpicked_create">
                                    <label class="switch-btn-slider" for="switch_is_handpicked_create"></label>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Features -->
        <div class="tab-pane fade" id="features-create" role="tabpanel">
            <div class="alert alert-info font-size-13 py-2 mb-3">
                <i class="fa fa-info-circle ml-1"></i>حدد المميزات والتجهيزات المتوفرة في هذه السيارة:
            </div>
            <div class="row p-2 border rounded bg-light" style="max-height: 320px; overflow-y: auto;">
                @foreach($features as $feature)
                    <div class="col-md-4 col-sm-6 mb-2">
                        <label class="feature-checkbox-card d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                @if($feature->icon)
                                    <span class="feature-icon-badge ml-2">{!! featureIcon($feature->icon) !!}</span>
                                @endif
                                <span class="font-weight-bold font-size-13 text-dark">{{ $feature->name_ar }}</span>
                            </div>
                            <div class="custom-control custom-checkbox m-0 p-0">
                                <input type="checkbox" class="custom-control-input feature-input" name="features[]" value="{{ $feature->id }}" id="feat_create_{{ $feature->id }}">
                                <label class="custom-control-label" for="feat_create_{{ $feature->id }}"></label>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 4: Media & Description -->
        <div class="tab-pane fade" id="media-create" role="tabpanel">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">الصورة الرئيسية (الغلاف) <span class="text-danger">*</span></label>
                    <input type="file" class="dropify" name="primary_image" data-height="160" required/>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">صور المعرض الإضافية (يمكن اختيار أكثر من صورة)</label>
                    <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*"/>
                    <small class="text-muted d-block mt-1">اضغط لاختيار صور إضافية للمعرض تظهر في تفاصيل السيارة.</small>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">وصف السيارة (بالعربية)</label>
                    <textarea class="form-control" rows="4" placeholder="اكتب وصفاً جذاباً للسيارة..." name="description_ar"></textarea>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">وصف السيارة (بالإنجليزية)</label>
                    <textarea class="form-control" dir="ltr" rows="4" placeholder="Car description in English..." name="description_en"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>
