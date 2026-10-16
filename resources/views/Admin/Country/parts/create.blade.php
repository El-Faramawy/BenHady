<form id="form" enctype="multipart/form-data" method="POST" action="{{route('countries.store')}}">
    @csrf
    <div class="row mt-0">

        <x-form.input class="col-sm-12" title="الاسم ( بالعربية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم ( بالعربية ) " name="name_ar"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الاسم ( بالانجليزية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم ( بالانجليزية ) " name="name_en"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الكود" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الكود " name="code"/>
        </x-form.input>
    </div>
</form>

