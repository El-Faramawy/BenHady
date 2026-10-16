<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('model_years.update', $modelYear->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="السنة (مثال: 2024)" :required="true">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="السنة" name="year" min="1900" max="2100" value="{{ $modelYear->year }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $modelYear->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$modelYear->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
