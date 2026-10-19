<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم الكامل" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم الكامل للعميل" name="name" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="رقم الهاتف" :required="true">
            <input type="text" class="form-control form-control-solid numbersOnly" placeholder="مثال: 501234567" name="phone" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="البريد الإلكتروني" :required="false">
            <input type="email" class="form-control form-control-solid" placeholder="example@domain.com" name="email"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="كلمة المرور" :required="true">
            <input type="password" class="form-control form-control-solid" placeholder="كلمة المرور" name="password" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="نوع المستخدم / الإقامة" :required="true">
            <select name="type" id="user_type_select" class="form-control form-control-solid" required>
                <option value="national_id">مواطن سعودي (هوية وطنية)</option>
                <option value="resident_id">مقيم (إقامة)</option>
                <option value="visitor">زائر / خليجي (جواز سفر أو رقم الحدود)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="حالة الحساب" :required="true">
            <select name="status" class="form-control form-control-solid" required>
                <option value="active">نشط / مفعل</option>
                <option value="pending">قيد المراجعة</option>
                <option value="in_active">غير نشط / موقوف</option>
                <option value="rejected">مرفوض</option>
            </select>
        </x-form.input>

        <!-- Fields for Citizen and Resident -->
        <div class="col-12 p-0" id="national_resident_wrapper">
            <div class="row m-0">
                <x-form.input class="col-sm-4" title="رقم الهوية / الإقامة" :required="true">
                    <input type="text" class="form-control form-control-solid resident-field numbersOnly" placeholder="رقم الهوية أو الإقامة" name="id_number" required/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="تاريخ انتهاء الهوية" :required="true">
                    <input type="date" class="form-control form-control-solid resident-field" name="id_number_end_date" required/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="رقم النسخة" :required="true">
                    <input type="text" class="form-control form-control-solid resident-field" placeholder="مثال: 1" name="version_number" required/>
                </x-form.input>
            </div>
        </div>

        <!-- Fields for Visitor -->
        <div class="col-12 p-0 d-none" id="visitor_wrapper">
            <div class="row m-0">
                <x-form.input class="col-sm-6" title="رقم الدخول الحدودي" :required="true">
                    <input type="text" class="form-control form-control-solid visitor-field numbersOnly" placeholder="رقم الحدود" name="border_entry_number"/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="رقم الرخصة" :required="true">
                    <input type="text" class="form-control form-control-solid visitor-field" placeholder="رقم الرخصة أو الجواز" name="license_number"/>
                </x-form.input>
            </div>
        </div>

        <x-form.input class="col-sm-4" title="تاريخ الميلاد" :required="false">
            <input type="date" class="form-control form-control-solid" name="date_of_birth"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="رقم رخصة القيادة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="رقم رخصة القيادة" name="driving_license_number"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="تاريخ انتهاء الرخصة" :required="false">
            <input type="date" class="form-control form-control-solid" name="license_expiry_date"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="رصيد المحفظة (ر.س)" :required="false">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" placeholder="0.00" name="wallet" value="0"/>
        </x-form.input>

        <div class="col-sm-6 mb-3">
            <label class="font-weight-bold d-block">حالات التوثيق</label>
            <div class="custom-controls-stacked mt-2">
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="phone_verified" value="1" checked>
                    <span class="custom-control-label">الهاتف موثق</span>
                </label>
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="id_verified" value="1">
                    <span class="custom-control-label">الهوية موثقة</span>
                </label>
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="licence_verified" value="1">
                    <span class="custom-control-label">الرخصة موثقة</span>
                </label>
            </div>
        </div>
    </div>

    <script>
        function toggleUserTypeFields() {
            var selectedType = $('#user_type_select').val();
            if (selectedType === 'visitor') {
                $('#national_resident_wrapper').addClass('d-none');
                $('#national_resident_wrapper .resident-field').prop('required', false);

                $('#visitor_wrapper').removeClass('d-none');
                $('#visitor_wrapper .visitor-field').prop('required', true);
            } else {
                $('#visitor_wrapper').addClass('d-none');
                $('#visitor_wrapper .visitor-field').prop('required', false);

                $('#national_resident_wrapper').removeClass('d-none');
                $('#national_resident_wrapper .resident-field').prop('required', true);
            }
        }

        $('#user_type_select').on('change', toggleUserTypeFields);
        toggleUserTypeFields();
    </script>
</form>
