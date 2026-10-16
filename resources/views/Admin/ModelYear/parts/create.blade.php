<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('model_years.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="السنة (مثال: 2024)" :required="true">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="السنة" name="year" min="1900" max="2100" value="{{ date('Y') }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
