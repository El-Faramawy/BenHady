<form id="workingHoursForm" method="POST" action="{{ route('branches.working_hours.update', $branch->id) }}">
    @csrf
    @method('PUT')

    <style>
        .day-status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            min-width: 155px;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
            <h5 class="font-weight-bold text-primary mb-1"><i class="fa fa-map-marker ml-2"></i>{{ $branch->name_ar }}</h5>
            <small class="text-muted">{{ $branch->city?->name_ar }} - {{ $branch->address_ar }}</small>
        </div>
        <span class="badge badge-primary px-3 py-2 font-size-12">7 أيام الأسبوع</span>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle mb-0" id="hoursTable">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width: 130px; min-width: 120px;">اليوم</th>
                    <th style="width: 190px; min-width: 180px;">تفعيل اليوم</th>
                    <th style="width: 130px; min-width: 120px;">24 ساعة</th>
                    <th>وقت الفتح</th>
                    <th>وقت الإغلاق</th>
                    <th>بداية الحجز</th>
                    <th>نهاية الحجز</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hours as $dayKey => $hour)
                    <tr class="day-row" data-day="{{ $dayKey }}">
                        <td class="font-weight-bold align-middle">
                            <input type="hidden" name="working_hours[{{ $loop->index }}][day_of_week]" value="{{ $dayKey }}">
                            <span class="badge badge-light p-2 font-size-13 text-dark border w-100">{{ $hour['label'] }}</span>
                        </td>
                        <td class="align-middle" style="width: 190px; min-width: 180px;">
                            <input type="hidden" name="working_hours[{{ $loop->index }}][is_open]" value="0">
                            <div class="day-status-pill">
                                <label class="custom-switch m-0 d-inline-flex align-items-center cursor-pointer" style="cursor: pointer;">
                                    <input type="checkbox" class="custom-switch-input is-open-toggle" id="open_{{ $dayKey }}" name="working_hours[{{ $loop->index }}][is_open]" value="1" {{ $hour['is_open'] ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description font-weight-bold status-label text-{{ $hour['is_open'] ? 'success' : 'danger' }}" style="margin-right: 20px !important; margin-left: 6px !important; font-size: 14px; user-select: none;">
                                        {{ $hour['is_open'] ? 'مفتوح' : 'مغلق' }}
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="working_hours[{{ $loop->index }}][is_24_hours]" value="0">
                            <div class="d-inline-flex align-items-center justify-content-center px-2 py-1 bg-light rounded border" style="min-width: 110px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input is-24h-toggle" id="h24_{{ $dayKey }}" name="working_hours[{{ $loop->index }}][is_24_hours]" value="1" {{ $hour['is_24_hours'] ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="h24_{{ $dayKey }}" style="cursor: pointer; font-size: 13px; user-select: none; margin-bottom: 0;">24 ساعة</label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="time" class="form-control form-control-sm text-center font-weight-bold time-input open-at" style="min-width: 105px;" name="working_hours[{{ $loop->index }}][open_at]" value="{{ $hour['open_at'] }}" {{ !$hour['is_open'] || $hour['is_24_hours'] ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="time" class="form-control form-control-sm text-center font-weight-bold time-input close-at" style="min-width: 105px;" name="working_hours[{{ $loop->index }}][close_at]" value="{{ $hour['close_at'] }}" {{ !$hour['is_open'] || $hour['is_24_hours'] ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="time" class="form-control form-control-sm text-center font-weight-bold time-input res-start" style="min-width: 105px;" name="working_hours[{{ $loop->index }}][reservation_start_at]" value="{{ $hour['reservation_start_at'] }}" {{ !$hour['is_open'] || $hour['is_24_hours'] ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="time" class="form-control form-control-sm text-center font-weight-bold time-input res-close" style="min-width: 105px;" name="working_hours[{{ $loop->index }}][reservation_close_at]" value="{{ $hour['reservation_close_at'] }}" {{ !$hour['is_open'] || $hour['is_24_hours'] ? 'disabled' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal-footer px-0 pb-0 mt-3 d-flex justify-content-between">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary px-5 font-weight-bold">
            <i class="fa fa-save ml-1"></i>حفظ مواعيد العمل
        </button>
    </div>
</form>

<script>
    (function() {
        // Toggle Open / Closed
        $(document).off('change.is_open', '.is-open-toggle').on('change.is_open', '.is-open-toggle', function() {
            var $row = $(this).closest('.day-row');
            var isOpen = $(this).is(':checked');
            var $label = $row.find('.status-label');
            var is24h = $row.find('.is-24h-toggle').is(':checked');

            if (isOpen) {
                $label.text('مفتوح').removeClass('text-danger').addClass('text-success');
                $row.find('.is-24h-toggle').prop('disabled', false);
                $row.find('.time-input').prop('disabled', is24h);
            } else {
                $label.text('مغلق').removeClass('text-success').addClass('text-danger');
                $row.find('.is-24h-toggle').prop('disabled', true);
                $row.find('.time-input').prop('disabled', true);
            }
        });

        // Toggle 24 Hours
        $(document).off('change.is_24h', '.is-24h-toggle').on('change.is_24h', '.is-24h-toggle', function() {
            var $row = $(this).closest('.day-row');
            var is24h = $(this).is(':checked');
            var isOpen = $row.find('.is-open-toggle').is(':checked');

            if (is24h && isOpen) {
                $row.find('.open-at').val('00:00');
                $row.find('.close-at').val('23:59');
                $row.find('.res-start').val('00:00');
                $row.find('.res-close').val('23:59');
                $row.find('.time-input').prop('disabled', true);
            } else if (isOpen) {
                $row.find('.time-input').prop('disabled', false);
            }
        });
    })();
</script>
