<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('brands.update', $brand->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar" value="{{ $brand->name_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en" value="{{ $brand->name_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="{{ $brand->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $brand->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$brand->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-12" title="شعار الماركة" :required="false">
            <input type="file" class="form-control form-control-solid" name="logo" accept="image/*"/>
            @if($brand->logo_url)
                <div class="mt-2">
                    <img src="{{ get_file($brand->logo_url) }}" style="width: 54px; height: 54px; object-fit: contain; padding: 4px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;" class="preview-img rounded shadow-sm" onerror="this.onerror=null;this.src='{{ asset('Admin/imgs/default.jpg') }}';" alt="brand logo">
                </div>
            @endif
        </x-form.input>
    </div>
</form>
