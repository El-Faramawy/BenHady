<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('branches.update', $branch->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="المدينة" :required="true">
            <select name="city_id" class="form-control form-control-solid">
                <option value="">اختر المدينة</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ $branch->city_id == $city->id ? 'selected' : '' }}>{{ $city->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $branch->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$branch->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالعربية" name="name_ar" value="{{ $branch->name_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الاسم بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم بالإنجليزية" name="name_en" value="{{ $branch->name_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="العنوان بالعربية" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="العنوان بالعربية" name="address_ar" value="{{ $branch->address_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="العنوان بالإنجليزية" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="العنوان بالإنجليزية" name="address_en" value="{{ $branch->address_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="رقم الهاتف" :required="false">
            <input type="text" class="form-control form-control-solid numbersOnly" placeholder="رقم الهاتف" name="phone" value="{{ $branch->phone }}"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="خط العرض (Latitude)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="Latitude" name="latitude" value="{{ $branch->latitude }}"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="خط الطول (Longitude)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="Longitude" name="longitude" value="{{ $branch->longitude }}"/>
        </x-form.input>
    </div>
</form>
