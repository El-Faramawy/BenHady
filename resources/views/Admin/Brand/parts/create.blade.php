<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('brands.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-12" title="شعار الماركة" :required="false">
            <input type="file" class="form-control form-control-solid" name="logo" accept="image/*"/>
        </x-form.input>
    </div>
</form>
