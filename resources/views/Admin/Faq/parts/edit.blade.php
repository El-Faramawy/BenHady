<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('faqs.update', $faq->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="السؤال بالعربية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="السؤال بالعربية" name="question_ar" value="{{ $faq->question_ar }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="السؤال بالإنجليزية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="السؤال بالإنجليزية" name="question_en" value="{{ $faq->question_en }}"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الإجابة بالعربية" :required="true">
            <textarea class="form-control form-control-solid" rows="3" placeholder="الإجابة بالعربية" name="answer_ar">{{ $faq->answer_ar }}</textarea>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الإجابة بالإنجليزية" :required="true">
            <textarea class="form-control form-control-solid" rows="3" placeholder="الإجابة بالإنجليزية" name="answer_en">{{ $faq->answer_en }}</textarea>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="{{ $faq->sort_order }}"/>
        </x-form.input>
    </div>
</form>
