<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Arabic)
    |--------------------------------------------------------------------------
    */

    'accepted'             => 'يجب قبول حقل :attribute.',
    'accepted_if'          => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url'           => 'حقل :attribute لا يمثل رابطًا صحيحًا.',
    'after'                => 'يجب أن يكون حقل :attribute تاريخًا بعد :date.',
    'after_or_equal'       => 'يجب أن يكون حقل :attribute تاريخًا يساوي أو بعد :date.',
    'alpha'                => 'يجب أن يحتوي حقل :attribute على حروف فقط.',
    'alpha_dash'           => 'يجب أن يحتوي حقل :attribute على حروف وأرقام وشرطات.',
    'alpha_num'            => 'يجب أن يحتوي حقل :attribute على حروف وأرقام فقط.',
    'array'                => 'يجب أن يكون حقل :attribute مصفوفة.',
    'before'               => 'يجب أن يكون حقل :attribute تاريخًا قبل :date.',
    'before_or_equal'      => 'يجب أن يكون حقل :attribute تاريخًا يساوي أو قبل :date.',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف حقل :attribute بين :min و :max حرفًا.',
        'array'   => 'يجب أن يحتوي حقل :attribute على عدد من العناصر بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة حقل :attribute إما صحيحًا أو خاطئًا.',
    'confirmed'            => 'حقل تأكيد :attribute غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => 'حقل :attribute ليس تاريخًا صحيحًا.',
    'date_equals'          => 'يجب أن يكون حقل :attribute تاريخًا مطابقًا لـ :date.',
    'date_format'          => 'حقل :attribute لا يتوافق مع الشكل :format.',
    'different'            => 'يجب أن يكون الحقلان :attribute و :other مُختلفين.',
    'digits'               => 'يجب أن يحتوي حقل :attribute على :digits رقمًا.',
    'digits_between'       => 'يجب أن يحتوي حقل :attribute على عدد أرقام بين :min و :max.',
    'email'                => 'يجب أن يكون حقل :attribute عنوان بريد إلكتروني صحيح.',
    'ends_with'            => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'enum'                 => 'القيمة المحددة لـ :attribute غير صالحة.',
    'exists'               => 'القيمة المحددة لـ :attribute غير موجودة.',
    'file'                 => 'يجب أن يكون حقل :attribute ملفًا.',
    'filled'               => 'حقل :attribute إجباري.',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكثر من :value حرفًا.',
        'array'   => 'يجب أن يحتوي حقل :attribute على أكثر من :value عناصر.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكثر من أو يساوي :value حرفًا.',
        'array'   => 'يجب أن يحتوي حقل :attribute على :value عناصر أو أكثر.',
    ],
    'image'                => 'يجب أن يكون حقل :attribute صورة.',
    'in'                   => 'القيمة المحددة لـ :attribute غير صالحة.',
    'integer'              => 'يجب أن يكون حقل :attribute عددًا صحيحًا.',
    'json'                 => 'يجب أن يكون حقل :attribute نصًا من نوع JSON.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أقل من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من :value حرفًا.',
        'array'   => 'يجب أن يحتوي حقل :attribute على أقل من :value عناصر.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أقل من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من أو يساوي :value حرفًا.',
        'array'   => 'يجب ألا يحتوي حقل :attribute على أكثر من :value عناصر.',
    ],
    'max'                  => [
        'numeric' => 'يجب ألا تكون قيمة حقل :attribute أكبر من :max.',
        'file'    => 'يجب ألا يكون حجم الملف :attribute أكبر من :max كيلوبايت.',
        'string'  => 'يجب ألا يتجاوز طول النص :attribute :max حرفًا.',
        'array'   => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عناصر.',
    ],
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute على الأقل :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute :min حروف على الأقل.',
        'array'   => 'يجب أن يحتوي حقل :attribute على :min عناصر على الأقل.',
    ],
    'not_in'               => 'القيمة المحددة لـ :attribute غير صالحة.',
    'numeric'              => 'يجب أن يكون حقل :attribute رقمًا.',
    'password'             => [
        'letters'       => 'يجب أن يحتوي حقل :attribute على حرف واحد على الأقل.',
        'mixed'         => 'يجب أن يحتوي حقل :attribute على حرف كبير وحرف صغير على الأقل.',
        'numbers'       => 'يجب أن يحتوي حقل :attribute على رقم واحد على الأقل.',
        'symbols'       => 'يجب أن يحتوي حقل :attribute على رمز واحد على الأقل.',
        'uncompromised' => 'حقل :attribute المُدخل ظهر في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'required'             => 'حقل :attribute مطلوب.',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless'      => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with'        => 'حقل :attribute مطلوب إذا توفر :values.',
    'required_with_all'    => 'حقل :attribute مطلوب إذا توفر :values.',
    'required_without'     => 'حقل :attribute مطلوب إذا لم يتوفر :values.',
    'required_without_all' => 'حقل :attribute مطلوب إذا لم يتوفر أي من :values.',
    'same'                 => 'يجب أن يتطابق حقل :attribute مع :other.',
    'string'               => 'يجب أن يكون حقل :attribute نصًا.',
    'unique'               => 'قيمة حقل :attribute مستخدمة من قبل.',
    'url'                  => 'رابط حقل :attribute غير صحيح.',
    'uuid'                 => 'حقل :attribute يجب أن يكون رمز UUID صحيح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name'                   => 'الاسم',
        'email'                  => 'البريد الإلكتروني',
        'phone'                  => 'رقم الهاتف',
        'date_of_birth'          => 'تاريخ الميلاد',
        'type'                   => 'نوع المستخدم',
        'password'               => 'كلمة المرور',
        'password_confirmation'  => 'تأكيد كلمة المرور',
        'driving_license_number' => 'رقم رخصة القيادة',
        'license_expiry_date'    => 'تاريخ انتهاء الرخصة',
        'id_number'              => 'رقم الهوية / الإقامة',
        'id_number_end_date'     => 'تاريخ انتهاء الهوية / الإقامة',
        'version_number'         => 'رقم النسخة',
        'license_number'         => 'رقم الترخيص',
        'border_entry_number'    => 'رقم الدخول الحدودي',
        'identifier'             => 'رقم الهوية / الإقامة / الحدود',
        'code'                   => 'كود التحقق',
        'language'               => 'اللغة',
        'status'                 => 'الحالة',
        'city_id'                => 'المدينة',
        'pickup_date'            => 'تاريخ الاستلام',
        'pickup_time'            => 'وقت الاستلام',
    ],

];
