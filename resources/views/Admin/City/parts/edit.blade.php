<link href="{{url('Admin')}}/assets/plugins/select2/select2.min.css" rel="stylesheet"/>
<x-form.map lat="{{$city->latitude}}" long="{{$city->longitude}}"></x-form.map>
<form id="form" enctype="multipart/form-data" method="POST" action="{{route('cities.update',$city->id)}}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-12" title="البلد " :required="true">
            <select name="country_id" class="form-control form-control-solid select2" data-placeholder="اختر البلد" >
                <option value="" selected disabled>البلد</option>
                @foreach($countries as $country)
                    <option value="{{$country->id}}" {{$city->country_id == $country->id ? 'selected' : ''}}>{{$country->name_ar}}</option>
                @endforeach
            </select>
        </x-form.input>
        <x-form.input class="col-sm-12" title="الاسم ( بالعربية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم ( بالعربية ) " name="name_ar" value="{{$city->name_ar}}"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الاسم ( بالانجليزية ) " :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم ( بالانجليزية ) " name="name_en" value="{{$city->name_en}}"/>
        </x-form.input>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <input type="text" class="form-control" placeholder="خط الطول مثال : 24.815310807697905" name="latitude" value="{{$city->latitude}}" id="lat">
        </div>
        <div class="col-6">
            <input type="text" class="form-control" placeholder="دائرة العرض مثال : 46.67454711582318" name="longitude" value="{{$city->longitude}}" id="lng">
        </div>
    </div>
    <div id="map" style="height:400px" class="my-3 col-12"></div>
</form>

<script src="{{url('Admin')}}/assets/plugins/select2/select2.full.min.js"></script>
<script src="{{url('Admin')}}/assets/js/select2.js"></script>
