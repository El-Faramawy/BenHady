@php
    $selectedFeatures = $car->features->pluck('id')->toArray();
    $selectedBranches = $car->branches->pluck('id')->toArray();
@endphp

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('cars.update', $car->id) }}">
    @csrf
    @method('PUT')

    <!-- Nav tabs -->
    <ul class="nav car-nav-tabs mb-4" id="carEditTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-edit-tab" data-toggle="tab" href="#general-edit" role="tab">
                <i class="fa fa-info-circle ml-1"></i>البيانات الأساسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="specs-edit-tab" data-toggle="tab" href="#specs-edit" role="tab">
                <i class="fa fa-cogs ml-1"></i>المواصفات والفروع
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="features-edit-tab" data-toggle="tab" href="#features-edit" role="tab">
                <i class="fa fa-check-square-o ml-1"></i>المميزات والتجهيزات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="media-edit-tab" data-toggle="tab" href="#media-edit" role="tab">
                <i class="fa fa-picture-o ml-1"></i>الصور والوصف
            </a>
        </li>
    </ul>

    <!-- Tab contents -->
    <div class="tab-content" id="carEditTabContent">
        <!-- Tab 1: General Info -->
        <div class="tab-pane fade show active" id="general-edit" role="tabpanel">
            <div class="row">
                <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
                    <input type="text" class="form-control form-control-solid" placeholder="مثال: تويوتا كامري 2025" name="name_ar" value="{{ $car->name_ar }}" required/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
                    <input type="text" class="form-control form-control-solid" placeholder="Example: Toyota Camry 2025" name="name_en" value="{{ $car->name_en }}" required/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الماركة / الشركة المصنعة" :required="true">
                    <select name="brand_id" class="form-control form-control-solid" required>
                        <option value="">اختر الماركة</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $car->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name_ar }} ({{ $brand->name_en }})</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="قسم / فئة السيارة" :required="true">
                    <select name="category_id" class="form-control form-control-solid" required>
                        <option value="">اختر القسم</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $car->category_id == $category->id ? 'selected' : '' }}>{{ $category->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-4" title="سنة الصنع" :required="true">
                    <select name="model_year_id" class="form-control form-control-solid" required>
                        <option value="">اختر السنة</option>
                        @foreach($modelYears as $year)
                            <option value="{{ $year->id }}" {{ $car->model_year_id == $year->id ? 'selected' : '' }}>{{ $year->year }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-4" title="السعر اليومي (ر.س)" :required="true">
                    <input type="number" step="0.01" min="0" class="form-control form-control-solid" placeholder="مثال: 250" name="daily_price" value="{{ $car->daily_price }}" required/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="عدد المقاعد" :required="true">
                    <input type="number" min="1" max="50" class="form-control form-control-solid" placeholder="مثال: 5" name="seats" value="{{ $car->seats }}" required/>
                </x-form.input>
            </div>
        </div>

        <!-- Tab 2: Specs & Branches -->
        <div class="tab-pane fade" id="specs-edit" role="tabpanel">
            <div class="row">
                <x-form.input class="col-sm-6" title="نوع ناقل الحركة" :required="true">
                    <select name="transmission_id" class="form-control form-control-solid" required>
                        <option value="">اختر ناقل الحركة</option>
                        @foreach($transmissions as $transmission)
                            <option value="{{ $transmission->id }}" {{ $car->transmission_id == $transmission->id ? 'selected' : '' }}>{{ $transmission->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="نوع الوقود" :required="true">
                    <select name="fuel_type_id" class="form-control form-control-solid" required>
                        <option value="">اختر نوع الوقود</option>
                        @foreach($fuelTypes as $fuelType)
                            <option value="{{ $fuelType->id }}" {{ $car->fuel_type_id == $fuelType->id ? 'selected' : '' }}>{{ $fuelType->name_ar }}</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="الفرع المتواجدة به السيارة حالياً" :required="true">
                    <select name="branch_id" class="form-control form-control-solid" required>
                        <option value="">اختر الفرع الرئيسي</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $car->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name_ar }} ({{ $branch->city?->name_ar }})</option>
                        @endforeach
                    </select>
                </x-form.input>

                <x-form.input class="col-sm-6" title="حالة السيارة الحالية" :required="true">
                    @php $statusVal = is_object($car->status) ? $car->status->value : (string) $car->status; @endphp
                    <select name="status" class="form-control form-control-solid" required>
                        <option value="available" {{ $statusVal === 'available' ? 'selected' : '' }}>متاحة للطلب (Available)</option>
                        <option value="rented" {{ $statusVal === 'rented' ? 'selected' : '' }}>مؤجرة حالياً (Rented)</option>
                        <option value="maintenance" {{ $statusVal === 'maintenance' ? 'selected' : '' }}>في الصيانة (Maintenance)</option>
                        <option value="reserved" {{ $statusVal === 'reserved' ? 'selected' : '' }}>محجوزة (Reserved)</option>
                    </select>
                </x-form.input>

                <div class="col-sm-12 mb-3">
                    <label class="font-weight-bold">الفروع الإضافية المتاح استلام السيارة منها (اختياري)</label>
                    <div class="row p-2 border rounded bg-light" style="max-height: 140px; overflow-y: auto;">
                        @foreach($branches as $branch)
                            <div class="col-md-4 col-sm-6">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="branches[]" value="{{ $branch->id }}" {{ in_array($branch->id, $selectedBranches) ? 'checked' : '' }}>
                                    <span class="custom-control-label font-size-13">{{ $branch->name_ar }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-12">
                    <label class="font-weight-bold d-block">خيارات العرض</label>
                    <div class="custom-controls-stacked mt-1">
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="is_active" value="1" {{ $car->is_active ? 'checked' : '' }}>
                            <span class="custom-control-label">تفعيل السيارة في التطبيق</span>
                        </label>
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="is_featured" value="1" {{ $car->is_featured ? 'checked' : '' }}>
                            <span class="custom-control-label">مميزة (Featured)</span>
                        </label>
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="is_handpicked" value="1" {{ $car->is_handpicked ? 'checked' : '' }}>
                            <span class="custom-control-label">مختارة بعناية (Handpicked)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Features -->
        <div class="tab-pane fade" id="features-edit" role="tabpanel">
            <div class="alert alert-info font-size-13 py-2 mb-3">
                <i class="fa fa-info-circle ml-1"></i>حدد المميزات والتجهيزات المتوفرة في هذه السيارة:
            </div>
            <div class="row p-2 border rounded bg-light" style="max-height: 280px; overflow-y: auto;">
                @foreach($features as $feature)
                    <div class="col-md-4 col-sm-6 mb-2">
                        <label class="custom-control custom-checkbox d-flex align-items-center mb-0" style="cursor: pointer;">
                            <input type="checkbox" class="custom-control-input" name="features[]" value="{{ $feature->id }}" {{ in_array($feature->id, $selectedFeatures) ? 'checked' : '' }}>
                            <span class="custom-control-label d-flex align-items-center">
                                @if($feature->icon)
                                    <span class="ml-2">{!! featureIcon($feature->icon) !!}</span>
                                @endif
                                <span class="font-weight-bold font-size-13">{{ $feature->name_ar }}</span>
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 4: Media & Description -->
        <div class="tab-pane fade" id="media-edit" role="tabpanel">
            <!-- Current Gallery Images -->
            @if($car->images->isNotEmpty())
                <div class="mb-4">
                    <label class="font-weight-bold d-block">صور السيارة الحالية في المعرض:</label>
                    <div class="row p-2 border rounded bg-light">
                        @foreach($car->images as $img)
                            <div class="col-md-3 col-sm-6 text-center mb-3">
                                <div class="position-relative p-2 bg-white rounded shadow-sm border">
                                    <img src="{{ get_file($img->image_path) }}" class="rounded mb-2" style="width: 100%; height: 90px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('Admin/imgs/default.jpg') }}';" alt="car image">
                                    @if($img->is_primary)
                                        <span class="badge badge-success d-block mb-1 font-size-11"><i class="fa fa-star ml-1"></i>الصورة الرئيسية</span>
                                    @else
                                        <button type="button" class="btn btn-outline-primary btn-xs btn-block mb-1 set-primary-btn" data-car-id="{{ $car->id }}" data-image-id="{{ $img->id }}">
                                            تعيين كرئيسية
                                        </button>
                                    @endif
                                    <label class="custom-control custom-checkbox text-danger font-size-12 mb-0">
                                        <input type="checkbox" class="custom-control-input" name="delete_images[]" value="{{ $img->id }}">
                                        <span class="custom-control-label text-danger">تحديد للحذف</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">تغيير الصورة الرئيسية (اختياري)</label>
                    <input type="file" class="dropify" name="primary_image" data-default-file="{{ $car->primaryImage ? get_file($car->primaryImage->image_path) : '' }}" data-height="150"/>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">إضافة صور جديدة للمعرض</label>
                    <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*"/>
                    <small class="text-muted d-block mt-1">يمكنك اختيار عدة صور جديدة لإضافتها إلى معرض صور السيارة.</small>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">وصف السيارة (بالعربية)</label>
                    <textarea class="form-control" rows="4" placeholder="اكتب وصفاً جذاباً للسيارة..." name="description_ar">{{ $car->description_ar }}</textarea>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="font-weight-bold">وصف السيارة (بالإنجليزية)</label>
                    <textarea class="form-control" dir="ltr" rows="4" placeholder="Car description in English..." name="description_en">{{ $car->description_en }}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).off('click', '.set-primary-btn').on('click', '.set-primary-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var carId = $btn.data('car-id');
        var imageId = $btn.data('image-id');
        var url = "{{ route('cars.set_primary_image', [':car', ':image']) }}".replace(':car', carId).replace(':image', imageId);

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: "{{ csrf_token() }}" },
            beforeSend: function () { $('#global-loader').show(); },
            success: function (data) {
                $('#global-loader').hide();
                my_toaster(data.message || 'تم التعيين كرئيسية بنجاح');
                // Reload edit view to reflect new primary image
                $('#form-load').load("{{ route('cars.edit', $car->id) }}");
                $('#exportexample').DataTable().ajax.reload(null, false);
            },
            error: function (data) {
                $('#global-loader').hide();
                handleAjaxError(data);
            }
        });
    });
</script>
