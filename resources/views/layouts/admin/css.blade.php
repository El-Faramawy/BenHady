<!-- META DATA -->
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="{{setting()->name}}">
<meta name="author" content="Ahmed Samir">
<meta name="csrf-token" content="{{csrf_token()}}">
<meta name="keywords" content="{{setting()->name}}, Hardy, hardy, Hardy CX, hardy cx, cx stations">

<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="{{get_file(setting()->fav_icon)}}" />

<!-- TITLE -->
<title>{{setting()->name}}</title>

<!-- BOOTSTRAP CSS -->
<link href="{{url('Admin')}}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<!-- STYLE CSS -->
<link href="{{url('Admin')}}/assets/css-rtl/style.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet" />

<!-- SIDE-MENU CSS -->
<link href="{{url('Admin')}}/assets/css-rtl/sidemenu.css" rel="stylesheet">

<!-- PERFECT SCROLL CSS -->
<link href="{{url('Admin')}}/assets/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet" />

<!-- CUSTOM SCROLL BAR CSS -->
<link href="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />

<!-- FONT-ICONS CSS -->
<link href="{{url('Admin')}}/assets/css/icons.css" rel="stylesheet" />

<!-- SIDEBAR CSS -->
<link href="{{url('Admin')}}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('Admin')}}/assets/colors/color1.css" />

<!-- TOASTIFY CSS -->
<link rel="stylesheet" type="text/css" href="{{url('Admin')}}/assets/plugins/toastify/toastify.min.css">

<!-- SELECT2 CSS -->
<link href="{{url('Admin')}}/assets/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- INTERNAL DATA TABLE CSS -->
<link href="{{url('Admin')}}/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css" rel="stylesheet" />

<!-- Cairo Font with Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

@stack('admin_css')

<style>
    /* ==========================================================================
       1. Typography & Global Design Variables
       ========================================================================== */
    * {
        font-family: 'Cairo', sans-serif;
    }

    :root {
        --primary-color: #76C419;
        --primary-hover: #63a913;
        --primary-active: #56940f;
        --primary-light: #F5FDEA;
        --primary-subtle: #EEF9E1;
        --primary-border: #D8F2B5;
        --primary-dark: #2E5A1C;
        --primary-darker: #1E3A1A;
        --primary-text: #1E3A1A;
        --primary-header: #1E3A1A;
        --primary-shadow: rgba(118, 196, 25, 0.28);
    }

    /* ==========================================================================
       2. Brand Accent & Buttons
       ========================================================================== */
    .bg-primary {
        background-color: #76C419 !important;
    }

    .text-primary {
        color: #2E5A1C !important;
    }

    .border-primary {
        border-color: #76C419 !important;
    }

    .btn-primary {
        background-color: #76C419 !important;
        border-color: #76C419 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(118, 196, 25, 0.25) !important;
        transition: all 0.2s ease-in-out !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #63a913 !important;
        border-color: #63a913 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(118, 196, 25, 0.4) !important;
        transform: translateY(-1px);
    }

    .btn-primary-light {
        background-color: #F5FDEA !important;
        color: #2E5A1C !important;
        border: 1px solid #D8F2B5 !important;
        font-weight: 600;
    }

    .btn-primary-light:hover {
        background-color: #2E5A1C !important;
        color: #ffffff !important;
        border-color: #2E5A1C !important;
    }

    /* Custom Table Action Buttons */
    .btn-action-hours {
        background-color: #f59e0b !important;
        border-color: #d97706 !important;
        color: #ffffff !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-hours:hover {
        background-color: #d97706 !important;
        border-color: #b45309 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4) !important;
    }

    .btn-action-details {
        background-color: #7c3aed !important;
        border-color: #6d28d9 !important;
        color: #ffffff !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-details:hover {
        background-color: #6d28d9 !important;
        border-color: #5b21b6 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.4) !important;
    }

    .btn-action-view {
        background-color: #0891b2 !important;
        border-color: #0e7490 !important;
        color: #ffffff !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-view:hover {
        background-color: #0e7490 !important;
        border-color: #155e75 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(8, 145, 178, 0.4) !important;
    }

    /* ==========================================================================
       3. Sidebar Active & Hover States
       ========================================================================== */
    .side-menu__item.active,
    .side-menu__item:hover {
        background: rgba(118, 196, 25, 0.12) !important;
        color: #2E5A1C !important;
        border-right: 4px solid #76C419 !important;
        font-weight: 700 !important;
    }

    .side-menu__item.active .side-menu__icon,
    .side-menu__item:hover .side-menu__icon {
        color: #76C419 !important;
    }

    .side-menu__item.active:after,
    .side-menu__item:hover:after {
        background: #76C419 !important;
        width: 3px !important;
    }

    .side-menu__item.active:before,
    .side-menu__item:hover:before,
    .slide-item.active:before,
    .slide-item:hover:before {
        background: #76C419 !important;
        border-color: #76C419 !important;
    }

    .slide.submenu.is-expanded a.active,
    .slide.submenu.is-expanded a:hover {
        color: #2E5A1C !important;
        font-weight: 700 !important;
    }

    .side-menu .side-menu__icon {
        font-size: 22px;
        font-weight: 500;
    }

    /* ==========================================================================
       4. Breadcrumbs & Page Titles
       ========================================================================== */
    .page-title {
        color: #151515 !important;
        font-weight: 800 !important;
    }

    .breadcrumb-item a {
        color: #3F5829 !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: color 0.2s ease !important;
    }

    .breadcrumb-item a:hover {
        color: #76C419 !important;
    }

    .breadcrumb-item.active {
        color: #1E3A1A !important;
        font-weight: 700 !important;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: #A3C585 !important;
    }

    /* ==========================================================================
       5. Cards & Containers
       ========================================================================== */
    .card {
        border: 1px solid #E8F0DD !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
    }

    .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #EDF4E5 !important;
        padding: 18px 24px !important;
    }

    .card-title {
        color: #151515 !important;
        font-weight: 800 !important;
        font-size: 17px !important;
    }

    /* ==========================================================================
       6. Tables & DataTables (Neutralize Blue & Apply Modern Green Palette)
       ========================================================================== */
    .table-primary,
    .table-primary > th,
    .table-primary > td,
    .table-primary tbody + tbody,
    .table-hover .table-primary:hover,
    .table-hover .table-primary:hover > td,
    .table-hover .table-primary:hover > th {
        background-color: transparent !important;
        color: inherit !important;
    }

    /* Table Header: Solid dark rich green with crisp white text universally (No linear-gradient) */
    table thead th,
    table thead tr th,
    table.dataTable thead th,
    .modern-admin-table thead th,
    .table thead th,
    .table thead.bg-light th,
    thead.admin-table-header th,
    thead.bg-light th,
    thead th {
        background: #1C3B1A !important;
        background-color: #1C3B1A !important;
        background-image: none !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
        border-bottom: 2px solid #76C419 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        padding: 14px 16px !important;
        letter-spacing: 0.3px;
        white-space: nowrap;
        vertical-align: middle !important;
    }

    table thead th,
    table thead th *,
    table thead tr th,
    table thead tr th *,
    table.dataTable thead th,
    table.dataTable thead th *,
    thead.admin-table-header th,
    thead.admin-table-header th *,
    .table thead th,
    .table thead th * {
        color: #ffffff !important;
    }

    /* Checkboxes */
    table thead input[type="checkbox"],
    table tbody input[type="checkbox"] {
        accent-color: #2E5A1C !important;
        transform: scale(1.15);
        cursor: pointer;
    }

    /* DataTables Sorting Arrows */
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc:after {
        color: #D4F4A5 !important;
        opacity: 0.8 !important;
    }

    /* Table Body */
    table tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #FBFDF9 !important;
    }

    table tbody tr:hover,
    table.dataTable tbody tr:hover {
        background-color: #F1FAE6 !important;
    }

    table tbody td,
    table.dataTable tbody td {
        color: #1E293B !important;
        font-size: 13.5px !important;
        border-top: 1px solid #EDF4E5 !important;
        border-bottom: 1px solid #EDF4E5 !important;
        padding: 13px 16px !important;
        vertical-align: middle !important;
    }

    /* DataTables Controls & Filters */
    div#exportexample_wrapper {
        width: 100%;
    }

    div#exportexample_filter {
        display: inline-block;
        float: left;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #D8E6C5 !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        background: #FAFCF7 !important;
        color: #1E293B !important;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #76C419 !important;
        box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.2) !important;
        background: #ffffff !important;
        outline: none !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #D8E6C5 !important;
        border-radius: 8px !important;
        padding: 5px 12px !important;
        background: #FAFCF7 !important;
        color: #1E293B !important;
    }

    .dataTables_wrapper .dataTables_info {
        color: #49672A !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        padding-top: 12px !important;
    }

    /* DataTables Pagination Styling */
    .dataTables_paginate {
        margin-top: 15px !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
    }

    .dataTables_paginate .pagination {
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #76C419 0%, #5ea312 100%) !important;
        border-color: #76C419 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(118, 196, 25, 0.35) !important;
        font-weight: 700 !important;
    }

    .page-item .page-link {
        color: #2E5A1C !important;
        border: 1px solid #E2EED3 !important;
        border-radius: 6px !important;
        margin: 0 3px !important;
        padding: 6px 14px !important;
        background: #ffffff !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px;
        transition: all 0.2s ease !important;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #F1FAE6 !important;
        border-color: #76C419 !important;
        color: #1B3C1B !important;
    }

    .page-item.disabled .page-link {
        color: #94a3b8 !important;
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
        opacity: 0.65 !important;
    }

    .dataTables_paginate .page-item.previous .page-link,
    .dataTables_paginate .page-item.next .page-link {
        padding: 6px 16px !important;
        font-weight: 700 !important;
    }

    /* Export Buttons & Colvis */
    .dt-buttons {
        display: inline-flex !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
        margin-bottom: 12px !important;
    }

    .dt-button,
    .dt-buttons .btn {
        background: #F5FDEA !important;
        color: #2E5A1C !important;
        border: 1px solid #D8F2B5 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
    }

    .dt-button:hover,
    .dt-buttons .btn:hover {
        background: #234913 !important;
        color: #ffffff !important;
        border-color: #234913 !important;
    }

    /* Colvis Dropdown & Collections */
    div.dt-button-collection,
    ul.dt-button-collection,
    div.dt-button-collection.dropdown-menu,
    ul.dt-button-collection.dropdown-menu {
        display: block !important;
        position: absolute !important;
        direction: rtl !important;
        text-align: right !important;
        z-index: 99999 !important;
        background: #ffffff !important;
        border: 1.5px solid #76C419 !important;
        border-radius: 12px !important;
        box-shadow: 0 12px 32px rgba(27, 58, 22, 0.22) !important;
        padding: 8px 10px !important;
        min-width: 220px !important;
        max-width: 320px !important;
        max-height: 420px !important;
        overflow-y: auto !important;
        box-sizing: border-box !important;
    }

    div.dt-button-collection .dt-button,
    ul.dt-button-collection .dt-button,
    div.dt-button-collection a.dropdown-item,
    ul.dt-button-collection li a,
    ul.dt-button-collection a.dt-button {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        padding: 9px 14px !important;
        margin: 3px 0 !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        font-family: 'Cairo', sans-serif !important;
        color: #2D3748 !important;
        background: #F8FAFC !important;
        border: 1px solid #E2E8F0 !important;
        cursor: pointer !important;
        text-align: right !important;
        transition: all 0.2s ease !important;
        box-sizing: border-box !important;
    }

    div.dt-button-collection .dt-button:hover,
    ul.dt-button-collection .dt-button:hover,
    div.dt-button-collection a.dropdown-item:hover,
    ul.dt-button-collection li a:hover,
    ul.dt-button-collection a.dt-button:hover {
        background: #EEF9E1 !important;
        color: #1E3A1A !important;
        border-color: #D8F2B5 !important;
        transform: translateX(-2px);
    }

    div.dt-button-collection .dt-button.active,
    ul.dt-button-collection .dt-button.active,
    div.dt-button-collection a.dropdown-item.active,
    ul.dt-button-collection li.active a,
    ul.dt-button-collection a.dt-button.active {
        background: #116e45 !important;
        color: #ffffff !important;
        border-color: #116e45 !important;
        box-shadow: 0 3px 8px rgba(17, 110, 69, 0.25) !important;
    }

    div.dt-button-collection .dt-button.active span,
    ul.dt-button-collection .dt-button.active span,
    ul.dt-button-collection li.active a span {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    div.dt-button-collection .dt-button.active::after {
        content: "✓";
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin-right: 8px;
    }

    div.dt-button-collection .dt-button:not(.active)::after {
        content: "✕";
        font-size: 11px;
        font-weight: 700;
        color: #A0AEC0;
        margin-right: 8px;
    }

    div.dt-button-background {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 99990 !important;
        background: rgba(0, 0, 0, 0.05) !important;
    }

    /* ==========================================================================
       7. Universal Navigation & Tabs (Pills & Tabs)
       ========================================================================== */
    .nav-tabs,
    .nav-pills,
    .booking-filter-tabs,
    .settings-nav-tabs,
    .policy-nav-tabs,
    .car-nav-tabs {
        background: #F4F8EE !important;
        border: 1px solid #DCECC8 !important;
        border-radius: 12px !important;
        padding: 6px !important;
        border-bottom: 1px solid #DCECC8 !important;
        gap: 8px;
    }

    .nav-tabs .nav-link,
    .nav-pills .nav-link,
    .booking-filter-tabs .nav-link,
    .settings-nav-tabs .nav-link,
    .policy-nav-tabs .nav-link,
    .car-nav-tabs .nav-link {
        background: #ffffff !important;
        color: #3F5829 !important;
        border: 1px solid #E2EED3 !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 10px 18px !important;
        transition: all 0.2s ease-in-out !important;
        text-decoration: none !important;
    }

    .nav-tabs .nav-link:hover,
    .nav-pills .nav-link:hover,
    .booking-filter-tabs .nav-link:hover,
    .settings-nav-tabs .nav-link:hover,
    .policy-nav-tabs .nav-link:hover,
    .car-nav-tabs .nav-link:hover {
        background: #EBF5DD !important;
        color: #1E3A1A !important;
        border-color: #CBE3B1 !important;
    }

    .nav-tabs .nav-link.active,
    .nav-pills .nav-link.active,
    .nav-tabs .nav-item.show .nav-link,
    .booking-filter-tabs .nav-link.active,
    .settings-nav-tabs .nav-link.active,
    .policy-nav-tabs .nav-link.active,
    .car-nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #234913 0%, #315F1C 100%) !important;
        color: #ffffff !important;
        border-color: #234913 !important;
        box-shadow: 0 4px 12px rgba(35, 73, 19, 0.25) !important;
    }

    .nav-tabs .nav-link.active i,
    .nav-pills .nav-link.active i,
    .booking-filter-tabs .nav-link.active i,
    .settings-nav-tabs .nav-link.active i,
    .policy-nav-tabs .nav-link.active i,
    .car-nav-tabs .nav-link.active i {
        color: #A3EE3B !important;
    }

    /* ==========================================================================
       8. Forms, Select2, and Modals
       ========================================================================== */
    .form-control:focus {
        border-color: #76C419 !important;
        box-shadow: 0 0 0 0.2rem rgba(118, 196, 25, 0.25) !important;
    }

    .form-control-solid {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #1e293b;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .form-control-solid:focus {
        background-color: #ffffff;
        border-color: #76C419 !important;
    }

    /* Global Select2 Styling */
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 44px !important;
        border: 1px solid #e2eed3 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
        font-size: 14px;
        color: #1e293b;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #76C419 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #EBF7DF !important;
        color: #1E3A1A !important;
        font-weight: 700 !important;
    }

    .select2-container--default .select2-results__option:hover {
        background-color: #F4F8EE !important;
        color: #1E3A1A !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #76C419 !important;
    }

    /* Completely hide original native select when Select2 is applied */
    select.select2-hidden-accessible,
    select.form-control.select2-hidden-accessible {
        display: none !important;
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        border: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* Select2 Dropdown in Modals */
    .select2-dropdown {
        z-index: 1060 !important;
        border: 1px solid #d1e8b0 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .select2-search--dropdown .select2-search__field {
        font-family: 'Cairo', sans-serif !important;
        border: 1px solid #76C419 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        outline: none !important;
    }

    /* Modal Dialog Standard Sizes */
    #Modal .modal-dialog {
        max-width: 900px !important;
    }

    /* Badges */
    .badge-primary-light {
        background-color: #F5FDEA !important;
        color: #2E5A1C !important;
        border: 1px solid #D8F2B5 !important;
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
    }

    /* Day status pill (used in Branch working hours) */
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

    /* ==========================================================================
       9. Table Images Hover Zoom & Feature Icons
       ========================================================================== */
    .table-img-link {
        display: inline-block;
        position: relative;
        cursor: pointer;
        text-decoration: none;
        z-index: 1;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 0;
        transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .table-img-link:hover {
        transform: scale(2);
        z-index: 99999 !important;
    }

    .table-img-link:hover img,
    .table-img-link:hover .table-img {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35) !important;
        background-color: #ffffff !important;
        border-radius: 8px !important;
    }

    table.dataTable tbody td {
        overflow: visible !important;
        vertical-align: middle !important;
    }

    table td img,
    .table-img {
        transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.25s ease;
        cursor: pointer;
        border-radius: 8px !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block;
    }

    table td img:hover,
    .table-img:hover {
        transform: scale(2);
        z-index: 99999 !important;
        position: relative;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35) !important;
        background-color: #ffffff !important;
    }

    .img:hover {
        transform: scale(2);
    }

    .img {
        width: 60px !important;
        height: 44px !important;
        border-radius: 8px !important;
        object-fit: cover !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .feature-img-static,
    .feature-img-static:hover,
    #features-create img,
    #features-create img:hover,
    #features-edit img,
    #features-edit img:hover,
    .features-card img,
    .features-card img:hover,
    .no-zoom,
    .no-zoom:hover {
        transform: none !important;
        transition: none !important;
        box-shadow: none !important;
        cursor: default !important;
    }

    /* ==========================================================================
       10. Universal Form Controls, Green Checkboxes, Toggle Switches & Select Options
       ========================================================================== */
    /* Universal Native Select & Form-Control Option Backgrounds */
    select.form-control,
    .form-select {
        cursor: pointer;
    }

    select.form-control:focus,
    .form-select:focus {
        border-color: #76C419 !important;
        box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.2) !important;
        outline: none !important;
    }

    select.form-control option:checked,
    select option:checked {
        background-color: #76C419 !important;
        color: #ffffff !important;
        font-weight: 700;
    }

    select.form-control option:hover,
    select option:hover {
        background-color: #EBF7DF !important;
        color: #1E3A1A !important;
    }

    /* Universal Native Checkbox and Radio Accent */
    input[type="checkbox"],
    input[type="radio"] {
        accent-color: #76C419 !important;
    }

    /* Bootstrap & Template Custom Checkbox Styling */
    .custom-checkbox .custom-control-label {
        cursor: pointer;
        user-select: none;
    }

    .custom-checkbox .custom-control-label::before {
        border-radius: 6px !important;
        border: 2px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
        top: 0.125rem !important;
        transition: all 0.2s ease-in-out !important;
    }

    .custom-checkbox .custom-control-label::after {
        top: 0.125rem !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
    }

    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #76C419 !important;
        background-color: #76C419 !important;
        box-shadow: 0 2px 6px rgba(118, 196, 25, 0.35) !important;
    }

    .custom-checkbox .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.2) !important;
    }

    /* Standard Custom Switch (Green Indicator) */
    .custom-switch-input:checked ~ .custom-switch-indicator {
        background: #76C419 !important;
        border-color: #76C419 !important;
    }

    .custom-switch-input:checked ~ .custom-switch-description {
        color: #1E3A1A !important;
        font-weight: 700 !important;
    }

    /* Modern On/Off Switch Button Cards (Display Options) */
    .toggle-switch-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        cursor: pointer;
        transition: all 0.25s ease-in-out;
        user-select: none;
        margin-bottom: 0;
        height: 100%;
    }

    .toggle-switch-card:hover {
        border-color: #cbd5e1 !important;
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .toggle-switch-card:has(.switch-btn-input:checked),
    .toggle-switch-card.is-checked {
        border-color: #76C419 !important;
        background: #f9fdf5 !important;
        box-shadow: 0 4px 12px rgba(118, 196, 25, 0.12) !important;
    }

    .toggle-switch-card .toggle-icon-wrap {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .toggle-switch-card:has(.switch-btn-input:checked) .toggle-icon-wrap,
    .toggle-switch-card.is-checked .toggle-icon-wrap {
        background: #ebf7df;
    }

    /* iOS-Style Sliding On/Off Switch Button */
    .custom-switch-btn {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
        margin: 0 !important;
        cursor: pointer;
    }

    .custom-switch-btn .switch-btn-input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .custom-switch-btn .switch-btn-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 30px;
        margin: 0 !important;
    }

    .custom-switch-btn .switch-btn-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: #ffffff;
        transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
    }

    .custom-switch-btn .switch-btn-input:checked + .switch-btn-slider {
        background-color: #76C419 !important;
        box-shadow: 0 2px 8px rgba(118, 196, 25, 0.4) !important;
    }

    .custom-switch-btn .switch-btn-input:checked + .switch-btn-slider:before {
        transform: translateX(22px);
    }

    /* Feature Checkbox Cards (Distinct border & background) */
    .feature-checkbox-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        user-select: none;
        margin-bottom: 0;
        height: 100%;
    }

    .feature-checkbox-card:hover {
        border-color: #a7d971 !important;
        background-color: #fbfdf9;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .feature-checkbox-card:has(.feature-input:checked),
    .feature-checkbox-card.is-checked {
        border-color: #76C419 !important;
        background-color: #F5FDEA !important;
        box-shadow: 0 4px 12px rgba(118, 196, 25, 0.15) !important;
    }

    .feature-checkbox-card .feature-icon-badge {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 8px;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .feature-checkbox-card:has(.feature-input:checked) .feature-icon-badge,
    .feature-checkbox-card.is-checked .feature-icon-badge {
        background: #76C419;
        color: #ffffff;
    }

    /* Branch Checkbox Cards */
    .branch-checkbox-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        user-select: none;
        margin-bottom: 0;
        height: 100%;
    }

    .branch-checkbox-card:hover {
        border-color: #a7d971 !important;
        background-color: #fbfdf9;
    }

    .branch-checkbox-card:has(.branch-input:checked),
    .branch-checkbox-card.is-checked {
        border-color: #76C419 !important;
        background-color: #F5FDEA !important;
        box-shadow: 0 2px 8px rgba(118, 196, 25, 0.12) !important;
    }

    /* Gallery Image Delete Checkbox Cards */
    .image-delete-checkbox-card {
        background: #fff5f5;
        border: 1.5px solid #fed7d7 !important;
        border-radius: 8px !important;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        user-select: none;
        margin-bottom: 0;
    }

    .image-delete-checkbox-card:hover {
        border-color: #feb2b2 !important;
        background: #fee2e2;
    }

    .image-delete-checkbox-card:has(.image-delete-input:checked),
    .image-delete-checkbox-card.is-checked {
        border-color: #e53e3e !important;
        background: #fed7d7 !important;
        box-shadow: 0 2px 8px rgba(229, 62, 62, 0.2) !important;
    }

    .image-delete-checkbox-card .custom-control-label::before {
        background: #ffffff !important;
        border: 2px solid #e53e3e !important;
        border-radius: 4px !important;
    }

    .image-delete-checkbox-card .custom-control-input:checked ~ .custom-control-label::before {
        background: #e53e3e !important;
        border-color: #e53e3e !important;
        box-shadow: 0 2px 6px rgba(229, 62, 62, 0.35) !important;
    }

    /* ==========================================================================
       Buttons & Primary Outline Accents (Set As Primary Image, View Car, etc.)
       ========================================================================== */
    .btn-outline-primary,
    .set-primary-btn {
        color: #2E5A1C !important;
        border-color: #76C419 !important;
        background-color: transparent !important;
        font-weight: 600 !important;
        transition: all 0.2s ease-in-out !important;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:focus,
    .btn-outline-primary:active,
    .set-primary-btn:hover,
    .set-primary-btn:focus,
    .set-primary-btn:active {
        color: #ffffff !important;
        background-color: #76C419 !important;
        border-color: #76C419 !important;
        box-shadow: 0 3px 8px rgba(118, 196, 25, 0.35) !important;
    }

    /* Back To Top Button */
    #back-to-top {
        background: #76C419 !important;
        color: #ffffff !important;
        border: 2px solid #76C419 !important;
        box-shadow: 0 4px 14px rgba(118, 196, 25, 0.35) !important;
        transition: all 0.25s ease-in-out !important;
    }

    #back-to-top:hover {
        background: #2E5A1C !important;
        border-color: #2E5A1C !important;
        color: #ffffff !important;
        transform: translateY(-3px);
    }

    #back-to-top i {
        color: #ffffff !important;
    }

    /* Footer Project Name Link */
    .footer a {
        color: #2E5A1C !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: color 0.2s ease !important;
    }

    .footer a:hover {
        color: #76C419 !important;
        text-decoration: underline !important;
    }

    /* ==========================================================================
       Select2 & Dropdown Styling (Theme Green Hover & Selected)
       ========================================================================== */
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted,
    .select2-container--default .select2-results__option:hover {
        background-color: #76C419 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option[aria-selected="true"],
    .select2-container--default .select2-results__option--selected {
        background-color: #F5FDEA !important;
        color: #2E5A1C !important;
        font-weight: 700 !important;
    }

    .select2-container--default .select2-results__option[aria-selected="true"].select2-results__option--highlighted {
        background-color: #63a913 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #76C419 !important;
        box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.2) !important;
    }

    select.form-control option:checked,
    select.form-control option:hover {
        background-color: #76C419 !important;
        color: #ffffff !important;
    }

    /* ==========================================================================
       Nav Tabs Styling (Settings, Policies, etc.)
       ========================================================================== */
    .settings-nav-tabs,
    .policy-nav-tabs {
        border-bottom: 2px solid #D8F2B5 !important;
        margin-bottom: 20px;
        gap: 6px;
    }

    .settings-nav-tabs .nav-link,
    .policy-nav-tabs .nav-link {
        border: 1px solid transparent !important;
        border-radius: 8px 8px 0 0 !important;
        padding: 10px 20px !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
        color: #64748b !important;
        background: transparent !important;
        transition: all 0.2s ease-in-out !important;
    }

    .settings-nav-tabs .nav-link:hover,
    .policy-nav-tabs .nav-link:hover {
        color: #2E5A1C !important;
        background: #F5FDEA !important;
        border-color: #D8F2B5 #D8F2B5 transparent !important;
    }

    .settings-nav-tabs .nav-link.active,
    .policy-nav-tabs .nav-link.active {
        color: #2E5A1C !important;
        background: #ffffff !important;
        border-color: #D8F2B5 #D8F2B5 #ffffff !important;
        border-bottom: 2px solid #ffffff !important;
        box-shadow: 0 -2px 6px rgba(118, 196, 25, 0.08) !important;
    }

    .settings-nav-tabs .nav-link i,
    .policy-nav-tabs .nav-link i {
        color: #76C419 !important;
    }

    /* ==========================================================================
       ApexCharts RTL & Legend Alignment Fixes
       ========================================================================== */
    .apexcharts-canvas {
        direction: rtl !important;
    }

    .apexcharts-legend {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        gap: 8px !important;
        direction: rtl !important;
        padding: 4px 10px !important;
    }

    .apexcharts-legend-series {
        display: inline-flex !important;
        align-items: center !important;
        margin: 3px 6px !important;
    }

    .apexcharts-legend-marker {
        margin-left: 8px !important;
        margin-right: 0 !important;
        flex-shrink: 0;
    }

    .apexcharts-legend-text {
        font-family: 'Cairo', sans-serif !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding-left: 0 !important;
    }

    /* ==========================================================================
       Global Loader & Skeleton Shimmer Loaders
       ========================================================================== */

    /* ── Arabic Car Loader ── */
    #global-loader {
        display: none;
        position: fixed;
        z-index: 50000 !important;
        inset: 0;
        background: rgba(10, 30, 8, 0.55) !important;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0;
    }
    #global-loader.active {
        display: flex !important;
    }

    /* Loader box */
    .gl-box {
        background: rgba(255,255,255,0.97);
        border-radius: 20px;
        padding: 36px 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(118,196,25,0.2);
        animation: gl-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes gl-pop {
        from { transform: scale(0.85); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    /* Car Loader Image */
    .gl-car-img {
        width: 130px;
        height: auto;
        display: block;
        object-fit: contain;
    }

    /* Dots pulse row */
    .gl-dots {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .gl-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #76C419;
        animation: gl-bounce 1.2s ease-in-out infinite;
    }
    .gl-dot:nth-child(1) { animation-delay: 0s; }
    .gl-dot:nth-child(2) { animation-delay: 0.2s; }
    .gl-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes gl-bounce {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.5; }
        40%            { transform: scale(1.1); opacity: 1;   }
    }

    /* Arabic loading text */
    .gl-text {
        font-family: 'Cairo', sans-serif !important;
        font-size: 15px;
        font-weight: 700;
        color: #1E3A1A;
        letter-spacing: 0.2px;
        margin: 0;
    }
    .gl-sub {
        font-family: 'Cairo', sans-serif !important;
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        margin: 0;
    }

    /* Old .loader-img compatibility */
    #global-loader .loader-img { display: none !important; }

    .linear-background {
        animation-duration: 1.4s;
        animation-fill-mode: forwards;
        animation-iteration-count: infinite;
        animation-name: greenShimmer;
        animation-timing-function: linear;
        background: #F5FDEA;
        background: linear-gradient(to right, #F5FDEA 8%, #E4F7CA 20%, #F5FDEA 36%);
        background-size: 800px 104px;
        height: 220px;
        position: relative;
        border-radius: 12px;
        border: 1px solid #D8F2B5;
    }

    @keyframes greenShimmer {
        0%   { background-position: -468px 0; }
        100% { background-position:  468px 0; }
    }

    /* ==========================================================================
       Profile Dropdown Menu Styling (Dashboard Green & Hover)
       ========================================================================== */
    .app-header .dropdown.profile-1 .dropdown-menu {
        border: 1px solid #D8F2B5 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 28px rgba(46, 90, 28, 0.12) !important;
        padding: 8px !important;
        min-width: 190px !important;
        background: #ffffff !important;
        margin-top: 8px !important;
    }

    .app-header .dropdown.profile-1 .dropdown-item {
        padding: 10px 14px !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.2s ease-in-out !important;
        margin-bottom: 4px;
    }

    .app-header .dropdown.profile-1 .dropdown-item:last-child {
        margin-bottom: 0;
    }

    .app-header .dropdown.profile-1 .dropdown-item .dropdown-icon {
        color: #76C419 !important;
        font-size: 17px !important;
        margin-left: 10px !important;
        margin-right: 0 !important;
        transition: all 0.2s ease-in-out !important;
    }

    .app-header .dropdown.profile-1 .dropdown-item:hover,
    .app-header .dropdown.profile-1 .dropdown-item:focus,
    .app-header .dropdown.profile-1 .dropdown-item.active {
        background-color: #F5FDEA !important;
        color: #2E5A1C !important;
        font-weight: 700 !important;
    }

    .app-header .dropdown.profile-1 .dropdown-item:hover .dropdown-icon,
    .app-header .dropdown.profile-1 .dropdown-item:focus .dropdown-icon {
        color: #2E5A1C !important;
        transform: scale(1.1);
    }

    .profile-user {
        border: 2px solid #76C419 !important;
        transition: all 0.2s ease;
    }

    .dropdown.profile-1:hover .profile-user {
        box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.25) !important;
    }
</style>