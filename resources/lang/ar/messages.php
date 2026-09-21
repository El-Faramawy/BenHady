<?php

return [
    'user' => [
        'login_success'    => 'تم تسجيل الدخول بنجاح',
        'register_success' => 'تم تسجيل المستخدم بنجاح',
        'logout_success'   => 'تم تسجيل الخروج بنجاح',
        'phone_verified_success' => 'تم التحقق من رقم الهاتف بنجاح',
        'profile_update_success' => 'تم تحديث الملف الشخصي بنجاح',
        'not_found'        => 'المستخدم غير موجود',
        'not_active'       => 'حسابك غير مفعل، يرجى التحقق من بريدك الإلكتروني',
    ],
    'auth' => [
        'unauthenticated'     => 'غير مصرح',
        'forbidden'           => 'ليس لديك صلاحية للوصول إلى هذا المورد',
        'invalid_credentials' => 'بيانات الدخول غير صحيحة',
        'invalid_verification_code' => 'كود التحقق غير صحيح',
        'otp_resent_success' => 'تم إعادة إرسال رمز التحقق بنجاح',
        'otp_cooldown_error' => 'لم يمضِ وقت كافٍ لإعادة إرسال رمز التحقق، يرجى المحاولة لاحقاً',
        'phone_not_verified' => 'يجب تأكيد رقم الهاتف أولاً قبل إكمال التسجيل',
        'register_step_one_success' => 'تم حفظ البيانات وإرسال رمز التحقق بنجاح',
    ],
    'general' => [
        'server_error'     => 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى لاحقاً.',
        'validation_error' => 'البيانات المدخلة غير صالحة.',
    ],
    'car_not_found' => 'السيارة غير موجودة',
    'days' => [
        'sunday'    => 'الأحد',
        'monday'    => 'الإثنين',
        'tuesday'   => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday'  => 'الخميس',
        'friday'    => 'الجمعة',
        'saturday'  => 'السبت',
    ],
    'notification' => [
        'not_found'             => 'الإشعار غير موجود',
        'delete_success'        => 'تم حذف الإشعار بنجاح',
        'token_saved_success'   => 'تم حفظ رمز الجهاز بنجاح',
        'token_deleted_success' => 'تم حذف رمز الجهاز بنجاح',
    ],
    'contact' => [
        'sent_success' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً',
    ],
    'booking' => [
        'created' => 'تم إنشاء الحجز بنجاح',
        'not_found' => 'الحجز غير موجود',
        'car_not_available' => 'هذه السيارة غير متاحة للحجز',
        'branch_closed' => 'الفرع مغلق في الوقت المطلوب',
        'overlap' => 'السيارة محجوزة بالفعل في هذه الفترة',
    ],
    'booking_status' => [
        'pending' => 'قيد الانتظار',
        'active' => 'نشطة',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
    ],
];
