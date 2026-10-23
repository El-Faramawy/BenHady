<style>
    /* Invoice & Print Specific Styles */
    .invoice-box {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2eed3;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-width: 900px;
        margin: 0 auto;
    }
    .invoice-table th, .invoice-table td {
        padding: 12px 16px;
    }
    .invoice-table thead th {
        background-color: #f4f8ee;
        color: #1E3A1A;
        font-weight: 700;
        border-bottom: 2px solid #D8F2B5;
    }
    .invoice-status-stamp {
        border: 2px dashed #76C419;
        color: #2E5A1C;
        font-weight: 800;
        padding: 6px 18px;
        border-radius: 8px;
        display: inline-block;
        font-size: 15px;
        letter-spacing: 1px;
        transform: rotate(-3deg);
    }
    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .app-sidebar, .app-header, .page-header, .btn-no-print, .main-footer {
            display: none !important;
        }
        .app-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .invoice-box {
            border: none !important;
            box-shadow: none !important;
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
        }
    }
</style>
