<?php $setting = App\Models\Setting::first(); ?>

<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('admin.home') }}">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        <x-sidebar-link href="{{ route('admin.home') }}">
            <i class="fe fe-home side-menu__icon"></i>
            <span class="side-menu__label">الرئيسية</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admins.index') }}">
            <i class="fe fe-user-check side-menu__icon"></i>
            <span class="side-menu__label">المشرفين</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('users.index') }}">
            <i class="fe fe-users side-menu__icon"></i>
            <span class="side-menu__label">المستخدمين</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('cars.index') }}">
            <i class="fa fa-car side-menu__icon"></i>
            <span class="side-menu__label">السيارات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('cars.details_catalog') }}">
            <i class="fe fe-layers side-menu__icon"></i>
            <span class="side-menu__label">كتالوج وتفاصيل السيارات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('brands.index') }}">
            <i class="fe fe-tag side-menu__icon"></i>
            <span class="side-menu__label">الماركات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('bookings.index') }}">
            <i class="fe fe-clipboard side-menu__icon"></i>
            <span class="side-menu__label">الحجوزات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('categories.index') }}">
            <i class="fe fe-grid side-menu__icon"></i>
            <span class="side-menu__label">الأقسام</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('cities.index') }}">
            <i class="fe fe-map side-menu__icon"></i>
            <span class="side-menu__label">المدن</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('branches.index') }}">
            <i class="fe fe-map-pin side-menu__icon"></i>
            <span class="side-menu__label">الفروع</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('model_years.index') }}">
            <i class="fe fe-calendar side-menu__icon"></i>
            <span class="side-menu__label">سنوات الصنع</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('transmissions.index') }}">
            <i class="fe fe-activity side-menu__icon"></i>
            <span class="side-menu__label">نواقل الحركة</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('fuel_types.index') }}">
            <i class="fe fe-droplet side-menu__icon"></i>
            <span class="side-menu__label">أنواع الوقود</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('features.index') }}">
            <i class="fe fe-check-square side-menu__icon"></i>
            <span class="side-menu__label">المميزات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('faqs.index') }}">
            <i class="fe fe-help-circle side-menu__icon"></i>
            <span class="side-menu__label">الأسئلة الشائعة</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('rental_policies.index') }}">
            <i class="fe fe-shield side-menu__icon"></i>
            <span class="side-menu__label">سياسات التأجير والإلغاء</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('settings.index') }}">
            <i class="fe fe-settings side-menu__icon"></i>
            <span class="side-menu__label">الإعدادات</span>
        </x-sidebar-link>
    </ul>
</aside>
<!--/APP-SIDEBAR-->