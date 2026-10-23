<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('bookings.store') }}">
    @csrf
    <div class="row mt-0">
        <!-- User selection via Select2 -->
        <x-form.input class="col-sm-12" title="اختيار العميل / المستخدم" :required="true">
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- ابحث باسم أو هاتف العميل --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->phone }})
                    </option>
                @endforeach
            </select>
        </x-form.input>

        <!-- Car Selection -->
        <x-form.input class="col-sm-6" title="السيارة المراد حجزها" :required="true">
            <select name="car_id" id="booking_car_select" class="form-control form-control-solid" required>
                <option value="">-- اختر السيارة --</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" 
                            data-price="{{ (float) $car->daily_price }}"
                            data-current-branch-id="{{ $car->branch_id }}"
                            data-branches='{{ $car->branches_json }}'>
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
                <option value="">-- اختر السيارة أولاً لعرض فروعها --</option>
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
            <input type="datetime-local" class="form-control form-control-solid" id="calc_pickup_at" name="pickup_at" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ now()->addHour()->format('Y-m-d\TH:00') }}" required/>
            <small id="availability_hint" class="font-size-11 mt-1 d-block"></small>
        </x-form.input>

        <x-form.input class="col-sm-6" title="موعد التسليم" :required="true">
            <input type="datetime-local" class="form-control form-control-solid" id="calc_return_at" name="return_at" min="{{ now()->addHour()->format('Y-m-d\TH:00') }}" value="{{ now()->addDays(2)->addHour()->format('Y-m-d\TH:00') }}" required/>
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
        <x-form.input class="col-sm-12 mb-3" title="ملاحظات وتفاصيل إضافية" :required="false">
            <textarea name="notes" class="form-control form-control-solid" rows="2" placeholder="ملاحظات الحجز (اختياري)"></textarea>
        </x-form.input>
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

        function fetchEarliestAvailability() {
            var carId = $('#booking_car_select').val();
            var branchId = $('#pickup_branch_select').val();
            if (!carId || !branchId) return;

            $('#availability_hint').html('<span class="text-muted"><i class="fa fa-spinner fa-spin ml-1 text-primary"></i>جاري جلب أول موعد متاح للاستلام...</span>');

            $.ajax({
                url: "{{ route('bookings.car_availability') }}",
                type: 'GET',
                data: { car_id: carId, branch_id: branchId },
                success: function (res) {
                    if (res && res.pickup_at) {
                        $('#calc_pickup_at').val(res.pickup_at);
                        $('#calc_pickup_at').attr('min', res.min_pickup_at);
                        if (res.return_at) {
                            $('#calc_return_at').val(res.return_at);
                            $('#calc_return_at').attr('min', res.pickup_at);
                        }
                        $('#availability_hint').html('<span class="text-success font-weight-600"><i class="fa fa-check-circle ml-1"></i>تم تحديد أقرب موعد متاح للاستلام وفق مواعيد الفرع والسيارة</span>');
                        recalculateBookingSummary();
                    }
                },
                error: function () {
                    $('#availability_hint').empty();
                }
            });
        }

        function updatePickupBranchesForSelectedCar() {
            var $carOption = $('#booking_car_select option:selected');
            var carPrice = $carOption.data('price');
            var currentBranchId = $carOption.data('current-branch-id');
            var branches = $carOption.data('branches');

            if (carPrice) {
                $('#calc_daily_price').val(parseFloat(carPrice).toFixed(2));
            }

            var $pickupSelect = $('#pickup_branch_select');
            $pickupSelect.empty();

            if (!branches || branches.length === 0) {
                $pickupSelect.append('<option value="">-- لا توجد فروع مسجلة لهذه السيارة --</option>');
                return;
            }

            $pickupSelect.append('<option value="">-- اختر فرع الاستلام --</option>');

            var hasSelected = false;
            $.each(branches, function (idx, branch) {
                var isCurrent = (currentBranchId && branch.id == currentBranchId);
                var isSelected = isCurrent || (!currentBranchId && idx === 0);
                if (isSelected) {
                    hasSelected = true;
                }
                var label = branch.name + (isCurrent ? ' (الفرع الحالي للسيارة)' : '');
                $pickupSelect.append('<option value="' + branch.id + '" ' + (isSelected ? 'selected' : '') + '>' + label + '</option>');
            });

            if (!hasSelected && currentBranchId) {
                $pickupSelect.val(currentBranchId);
            }

            // Default return branch to pickup branch if return branch is not manually selected yet
            if (!$('#return_branch_select').val() && $pickupSelect.val()) {
                $('#return_branch_select').val($pickupSelect.val());
            }

            // Immediately load the earliest available pickup slot
            fetchEarliestAvailability();
            recalculateBookingSummary();
        }

        $('#booking_car_select').on('change', function () {
            updatePickupBranchesForSelectedCar();
        });

        $('#pickup_branch_select').on('change', function () {
            if (!$('#return_branch_select').val() || $('#return_branch_select').data('auto_synced') !== false) {
                $('#return_branch_select').val($(this).val());
            }
            fetchEarliestAvailability();
        });

        $('#return_branch_select').on('change', function () {
            $(this).data('auto_synced', false);
        });

        $('#calc_pickup_at').on('change input', function () {
            var val = $(this).val();
            if (val) {
                $('#calc_return_at').attr('min', val);
                var currentReturn = $('#calc_return_at').val();
                if (currentReturn && new Date(currentReturn) <= new Date(val)) {
                    var pDate = new Date(val);
                    pDate.setDate(pDate.getDate() + 2);
                    var pad = function (n) { return n < 10 ? '0' + n : n; };
                    var newReturn = pDate.getFullYear() + '-' + pad(pDate.getMonth() + 1) + '-' + pad(pDate.getDate()) + 'T' + pad(pDate.getHours()) + ':' + pad(pDate.getMinutes());
                    $('#calc_return_at').val(newReturn);
                }
            }
            recalculateBookingSummary();
        });

        $('#calc_return_at, #calc_daily_price, #calc_discount').on('input change', recalculateBookingSummary);
        recalculateBookingSummary();
    </script>
</form>
