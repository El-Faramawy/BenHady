<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('branches.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="المدينة" :required="true">
            <select name="city_id" class="form-control form-control-solid">
                <option value="">اختر المدينة</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="العنوان بالعربية" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="العنوان بالعربية" name="address_ar"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="العنوان بالإنجليزية" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="العنوان بالإنجليزية" name="address_en"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="رقم الهاتف" :required="false">
            <input type="text" class="form-control form-control-solid numbersOnly" placeholder="رقم الهاتف" name="phone"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="خط العرض (Latitude)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="Latitude" name="latitude"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="خط الطول (Longitude)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="Longitude" name="longitude"/>
        </x-form.input>
    </div>
</form>
