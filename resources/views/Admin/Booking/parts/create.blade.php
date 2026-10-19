<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('bookings.store') }}">
    @csrf
    <div class="row mt-0">
        <!-- User selection via Select2 -->
        <div class="col-sm-12 mb-3">
            <label class="font-weight-bold font-size-13">اختيار العميل / المستخدم <span class="text-danger">*</span></label>
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- ابحث باسم أو هاتف العميل --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Car Selection -->
        <x-form.input class="col-sm-6" title="السيارة المراد حجزها" :required="true">
            <select name="car_id" id="booking_car_select" class="form-control form-control-solid" required>
                <option value="">-- اختر السيارة --</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" data-price="{{ (float) $car->daily_price }}">
                        {{ $car->name_ar }} ({{ $car->name_en }}) - {{ number_format((float) $car->daily_price, 2) }} ر.س/يوم
                    </option>
                @endforeach
            </select>
        </x-form.input>

        <!-- Status -->
        <x-form.input class="col-sm-6" title="حالة الحجز" :required="true">
            <select name="status" class="form-control form-control-solid" required>
                <option value="pending" selected>قيد الانتظار</option>
                <option value="active">نشط (تم الاستلام)</option>
                <option value="completed">مكتمل (تم التسليم)</option>
                <option value="cancelled">ملغي</option>
            </select>
        </x-form.input>

        <!-- Pickup & Return Branch -->
        <x-form.input class="col-sm-6" title="فرع الاستلام" :required="true">
            <select name="pickup_branch_id" id="pickup_branch_select" class="form-control form-control-solid" required>
                <option value="">-- اختر فرع الاستلام --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="فرع التسليم" :required="true">
            <select name="return_branch_id" id="return_branch_select" class="form-control form-control-solid" required>
                <option value="">-- اختر فرع التسليم --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <!-- Dates & Times -->
        <x-form.input class="col-sm-6" title="موعد الاستلام" :required="true">
            <input type="datetime-local" class="form-control form-control-solid" id="calc_pickup_at" name="pickup_at" value="{{ now()->addHour()->format('Y-m-d\TH:00') }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="موعد التسليم" :required="true">
            <input type="datetime-local" class="form-control form-control-solid" id="calc_return_at" name="return_at" value="{{ now()->addDays(2)->addHour()->format('Y-m-d\TH:00') }}" required/>
        </x-form.input>

        <!-- Pricing & Calculations -->
        <x-form.input class="col-sm-4" title="السعر اليومي (ر.س)" :required="false">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" id="calc_daily_price" name="daily_price" placeholder="السعر الافتراضي للسيارة"/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="قيمة الخصم إن وجد (ر.س)" :required="false">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" id="calc_discount" name="discount" value="0"/>
        </x-form.input>

        <!-- Summary Calculation Box -->
        <div class="col-sm-4 mb-3">
            <label class="font-weight-bold font-size-13 text-muted">ملخص الحسبة المقدرة</label>
            <div class="p-2 rounded bg-light border text-center">
                <span class="font-size-12 text-muted d-block">المدة: <strong id="calc_preview_days" class="text-primary">2</strong> يوم</span>
                <span class="font-size-14 text-success font-weight-bold">الإجمالي: <span id="calc_preview_total">0.00</span> ر.س</span>
            </div>
        </div>

        <!-- Notes -->
        <div class="col-sm-12 mb-3">
            <label class="font-weight-bold font-size-13">ملاحظات وتفاصيل إضافية</label>
            <textarea name="notes" class="form-control form-control-solid" rows="2" placeholder="ملاحظات الحجز (اختياري)"></textarea>
        </div>
    </div>

    <script>
        function recalculateBookingSummary() {
            var selectedCarPrice = parseFloat($('#booking_car_select option:selected').data('price')) || 0;
            var customDailyPrice = parseFloat($('#calc_daily_price').val());
            var dailyPrice = !isNaN(customDailyPrice) && customDailyPrice > 0 ? customDailyPrice : selectedCarPrice;

            if (isNaN(customDailyPrice) || customDailyPrice <= 0) {
                $('#calc_daily_price').val(selectedCarPrice > 0 ? selectedCarPrice.toFixed(2) : '');
            }

            var pickupVal = $('#calc_pickup_at').val();
            var returnVal = $('#calc_return_at').val();
            var days = 1;

            if (pickupVal && returnVal) {
                var pickupDate = new Date(pickupVal);
                var returnDate = new Date(returnVal);
                var diffMs = returnDate - pickupDate;
                if (diffMs > 0) {
                    var hours = diffMs / (1000 * 60 * 60);
                    days = Math.max(1, Math.ceil(hours / 24));
                }
            }

            var discount = parseFloat($('#calc_discount').val()) || 0;
            var subtotal = dailyPrice * days;
            var total = Math.max(0, subtotal - discount);

            $('#calc_preview_days').text(days);
            $('#calc_preview_total').text(total.toFixed(2));
        }

        $('#booking_car_select').on('change', function () {
            var carPrice = $(this).find('option:selected').data('price');
            if (carPrice) {
                $('#calc_daily_price').val(parseFloat(carPrice).toFixed(2));
            }
            recalculateBookingSummary();
        });

        $('#calc_pickup_at, #calc_return_at, #calc_daily_price, #calc_discount').on('input change', recalculateBookingSummary);
        recalculateBookingSummary();
    </script>
</form>
