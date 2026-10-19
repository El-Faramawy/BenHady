<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="الاسم الكامل" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم الكامل للعميل" name="name" value="{{ $user->name }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="رقم الهاتف" :required="true">
            <input type="text" class="form-control form-control-solid numbersOnly" placeholder="مثال: 501234567" name="phone" value="{{ $user->phone }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="البريد الإلكتروني" :required="false">
            <input type="email" class="form-control form-control-solid" placeholder="example@domain.com" name="email" value="{{ $user->email }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="كلمة المرور (اتركه فارغاً للإبقاء عليها)" :required="false">
            <input type="password" class="form-control form-control-solid" placeholder="كلمة مرور جديدة (اختياري)" name="password"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="نوع المستخدم / الإقامة" :required="true">
            <select name="type" id="user_type_select_edit" class="form-control form-control-solid" required>
                <option value="national_id" {{ $user->type === 'national_id' ? 'selected' : '' }}>مواطن سعودي (هوية وطنية)</option>
                <option value="resident_id" {{ $user->type === 'resident_id' ? 'selected' : '' }}>مقيم (إقامة)</option>
                <option value="visitor" {{ $user->type === 'visitor' ? 'selected' : '' }}>زائر / خليجي (جواز سفر أو رقم الحدود)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="حالة الحساب" :required="true">
            <select name="status" class="form-control form-control-solid" required>
                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>نشط / مفعل</option>
                <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="in_active" {{ $user->status === 'in_active' ? 'selected' : '' }}>غير نشط / موقوف</option>
                <option value="rejected" {{ $user->status === 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </x-form.input>

        <!-- Fields for Citizen and Resident -->
        <div class="col-12 p-0" id="national_resident_wrapper_edit">
            <div class="row m-0">
                <x-form.input class="col-sm-4" title="رقم الهوية / الإقامة" :required="true">
                    <input type="text" class="form-control form-control-solid resident-field numbersOnly" placeholder="رقم الهوية أو الإقامة" name="id_number" value="{{ $user->id_number }}"/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="تاريخ انتهاء الهوية" :required="true">
                    <input type="date" class="form-control form-control-solid resident-field" name="id_number_end_date" value="{{ $user->id_number_end_date }}"/>
                </x-form.input>

                <x-form.input class="col-sm-4" title="رقم النسخة" :required="true">
                    <input type="text" class="form-control form-control-solid resident-field" placeholder="مثال: 1" name="version_number" value="{{ $user->version_number }}"/>
                </x-form.input>
            </div>
        </div>

        <!-- Fields for Visitor -->
        <div class="col-12 p-0 d-none" id="visitor_wrapper_edit">
            <div class="row m-0">
                <x-form.input class="col-sm-6" title="رقم الدخول الحدودي" :required="true">
                    <input type="text" class="form-control form-control-solid visitor-field numbersOnly" placeholder="رقم الحدود" name="border_entry_number" value="{{ $user->border_entry_number }}"/>
                </x-form.input>

                <x-form.input class="col-sm-6" title="رقم الرخصة" :required="true">
                    <input type="text" class="form-control form-control-solid visitor-field" placeholder="رقم الرخصة أو الجواز" name="license_number" value="{{ $user->license_number }}"/>
                </x-form.input>
            </div>
        </div>

        <x-form.input class="col-sm-4" title="تاريخ الميلاد" :required="false">
            <input type="date" class="form-control form-control-solid" name="date_of_birth" value="{{ $user->date_of_birth }}"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="رقم رخصة القيادة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="رقم رخصة القيادة" name="driving_license_number" value="{{ $user->driving_license_number }}"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="تاريخ انتهاء الرخصة" :required="false">
            <input type="date" class="form-control form-control-solid" name="license_expiry_date" value="{{ $user->license_expiry_date }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="رصيد المحفظة (ر.س)" :required="false">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" placeholder="0.00" name="wallet" value="{{ $user->wallet }}"/>
        </x-form.input>

        <div class="col-sm-6 mb-3">
            <label class="font-weight-bold d-block">حالات التوثيق</label>
            <div class="custom-controls-stacked mt-2">
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="phone_verified" value="1" {{ $user->phone_verified ? 'checked' : '' }}>
                    <span class="custom-control-label">الهاتف موثق</span>
                </label>
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="id_verified" value="1" {{ $user->id_verified ? 'checked' : '' }}>
                    <span class="custom-control-label">الهوية موثقة</span>
                </label>
                <label class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="licence_verified" value="1" {{ $user->licence_verified ? 'checked' : '' }}>
                    <span class="custom-control-label">الرخصة موثقة</span>
                </label>
            </div>
        </div>
    </div>

    <script>
        function toggleUserTypeFieldsEdit() {
            var selectedType = $('#user_type_select_edit').val();
            if (selectedType === 'visitor') {
                $('#national_resident_wrapper_edit').addClass('d-none');
                $('#national_resident_wrapper_edit .resident-field').prop('required', false);

                $('#visitor_wrapper_edit').removeClass('d-none');
                $('#visitor_wrapper_edit .visitor-field').prop('required', true);
            } else {
                $('#visitor_wrapper_edit').addClass('d-none');
                $('#visitor_wrapper_edit .visitor-field').prop('required', false);

                $('#national_resident_wrapper_edit').removeClass('d-none');
                $('#national_resident_wrapper_edit .resident-field').prop('required', true);
            }
        }

        $('#user_type_select_edit').on('change', toggleUserTypeFieldsEdit);
        toggleUserTypeFieldsEdit();
    </script>
</form>
