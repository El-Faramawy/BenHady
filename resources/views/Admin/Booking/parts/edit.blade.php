<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('bookings.update', $booking->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <!-- Booking Number Display -->
        <div class="col-sm-12 mb-3">
            <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center">
                <span class="font-size-13 text-muted">رقم الحجز: <strong class="text-primary font-size-14">#{{ $booking->booking_number }}</strong></span>
                <span class="font-size-12 text-muted">تاريخ الإنشاء: {{ $booking->created_at?->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <!-- User selection via Select2 -->
        <div class="col-sm-12 mb-3">
            <label class="font-weight-bold font-size-13">العميل / المستخدم <span class="text-danger">*</span></label>
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- ابحث باسم أو هاتف العميل --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Car Selection -->
        <x-form.input class="col-sm-6" title="السيارة" :required="true">
            <select name="car_id" id="booking_car_select_edit" class="form-control form-control-solid" required>
                <option value="">-- اختر السيارة --</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" data-price="{{ (float) $car->daily_price }}" {{ $booking->car_id == $car->id ? 'selected' : '' }}>
                        {{ $car->name_ar }} ({{ $car->name_en }}) - {{ number_format((float) $car->daily_price, 2) }} ر.س/يوم
                    </option>
                @endforeach
            </select>
        </x-form.input>

        <!-- Status -->
        @php $statusVal = $booking->status instanceof \App\Enums\Booking\BookingStatusEnum ? $booking->status->value : (string) $booking->status; @endphp
        <x-form.input class="col-sm-6" title="حالة الحجز" :required="true">
            <select name="status" class="form-control form-control-solid" required>
                <option value="pending" {{ $statusVal === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>نشط (تم الاستلام)</option>
                <option value="completed" {{ $statusVal === 'completed' ? 'selected' : '' }}>مكتمل (تم التسليم)</option>
                <option value="cancelled" {{ $statusVal === 'cancelled' ? 'selected' : '' }}>ملغي</option>
            </select>
        </x-form.input>

        <!-- Pickup & Return Branch -->
        <x-form.input class="col-sm-6" title="فرع الاستلام" :required="true">
            <select name="pickup_branch_id" class="form-control form-control-solid" required>
                <option value="">-- اختر فرع الاستلام --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $booking->pickup_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="فرع التسليم" :required="true">
            <select name="return_branch_id" class="form-control form-control-solid" required>
                <option value="">-- اختر فرع التسليم --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $booking->return_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name_ar }}</option>
                @endforeach
            </select>
        </x-form.input>

        <!-- Dates & Times -->
        <x-form.input class="col-sm-6" title="موعد الاستلام" :required="true">
            <input type="datetime-local" class="form-control form-control-solid" id="calc_pickup_at_edit" name="pickup_at" value="{{ $booking->pickup_at?->format('Y-m-d\TH:i') }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="موعد التسليم" :required="true">
            <input type="datetime-local" class="form-control form-control-solid" id="calc_return_at_edit" name="return_at" value="{{ $booking->return_at?->format('Y-m-d\TH:i') }}" required/>
        </x-form.input>

        <!-- Pricing & Calculations -->
        <x-form.input class="col-sm-4" title="السعر اليومي (ر.س)" :required="true">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" id="calc_daily_price_edit" name="daily_price" value="{{ (float) $booking->daily_price }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-4" title="قيمة الخصم (ر.س)" :required="false">
            <input type="number" step="0.01" min="0" class="form-control form-control-solid" id="calc_discount_edit" name="discount" value="{{ (float) $booking->discount }}"/>
        </x-form.input>

        <!-- Summary Calculation Box -->
        <div class="col-sm-4 mb-3">
            <label class="font-weight-bold font-size-13 text-muted">ملخص الحسبة</label>
            <div class="p-2 rounded bg-light border text-center">
                <span class="font-size-12 text-muted d-block">المدة: <strong id="calc_preview_days_edit" class="text-primary">{{ $booking->total_days }}</strong> يوم</span>
                <span class="font-size-14 text-success font-weight-bold">الإجمالي: <span id="calc_preview_total_edit">{{ number_format((float) $booking->total, 2) }}</span> ر.س</span>
            </div>
        </div>

        <!-- Notes -->
        <div class="col-sm-12 mb-3">
            <label class="font-weight-bold font-size-13">ملاحظات الحجز</label>
            <textarea name="notes" class="form-control form-control-solid" rows="2" placeholder="ملاحظات الحجز">{{ $booking->notes }}</textarea>
        </div>
    </div>

    <script>
        function recalculateBookingSummaryEdit() {
            var selectedCarPrice = parseFloat($('#booking_car_select_edit option:selected').data('price')) || 0;
            var customDailyPrice = parseFloat($('#calc_daily_price_edit').val());
            var dailyPrice = !isNaN(customDailyPrice) && customDailyPrice > 0 ? customDailyPrice : selectedCarPrice;

            var pickupVal = $('#calc_pickup_at_edit').val();
            var returnVal = $('#calc_return_at_edit').val();
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

            var discount = parseFloat($('#calc_discount_edit').val()) || 0;
            var subtotal = dailyPrice * days;
            var total = Math.max(0, subtotal - discount);

            $('#calc_preview_days_edit').text(days);
            $('#calc_preview_total_edit').text(total.toFixed(2));
        }

        $('#booking_car_select_edit').on('change', function () {
            var carPrice = $(this).find('option:selected').data('price');
            if (carPrice) {
                $('#calc_daily_price_edit').val(parseFloat(carPrice).toFixed(2));
            }
            recalculateBookingSummaryEdit();
        });

        $('#calc_pickup_at_edit, #calc_return_at_edit, #calc_daily_price_edit, #calc_discount_edit').on('input change', recalculateBookingSummaryEdit);
    </script>
</form>
