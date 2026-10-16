<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('features.update', $feature->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar" value="{{ $feature->name_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en" value="{{ $feature->name_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الأيقونة" :required="false">
            <input type="file" class="form-control form-control-solid" name="icon" accept="image/*"/>
            @if($feature->icon)
                <div class="mt-2">
                    <img src="{{ get_file($feature->icon) }}" style="width: 48px; height: 48px; object-fit: contain; padding: 4px; background: #f8fafc; border: 1px solid #e2e8f0;" class="preview-img rounded shadow-sm" onerror="this.onerror=null;this.src='{{ asset('Admin/imgs/default.jpg') }}';" alt="feature icon">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $feature->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$feature->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
