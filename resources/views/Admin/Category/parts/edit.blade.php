<form id="form" enctype="multipart/form-data" method="POST" action="{{route('categories.update',$category->id)}}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-12" title="الإسم ( بالعربية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الإسم ( بالعربية ) " name="name_ar" value="{{$category->name_ar}}"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الإسم ( بالانجليزية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الإسم ( بالانجليزية ) " name="name_en" value="{{$category->name_en}}"/>
        </x-form.input>
    </div>

</form>
