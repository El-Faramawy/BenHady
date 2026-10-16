<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar" value="{{ $category->name_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en" value="{{ $category->name_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="{{ $category->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $category->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$category->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الأيقونة" :required="false">
            <input type="file" class="form-control form-control-solid" name="icon" accept="image/*"/>
            @if($category->icon)
                <div class="mt-2">
                    <img src="{{ get_file($category->icon) }}" style="width: 48px; height: 48px; object-fit: contain; padding: 4px; background: #f8fafc; border: 1px solid #e2e8f0;" class="preview-img rounded shadow-sm" onerror="this.onerror=null;this.src='{{ asset('Admin/imgs/default.jpg') }}';" alt="category icon">
                </div>
            @endif
        </x-form.input>
    </div>
</form>
