<style>
    /* ==========================================================================
       Dashboard Specific Styles
       ========================================================================== */

    /* Hero Welcome Banner */
    .dashboard-hero {
        background: linear-gradient(135deg, #F5FDEA 0%, #FFFFFF 100%);
        border: 1px solid #D8F2B5;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(118, 196, 25, 0.08);
    }
    .dashboard-hero::after {
        content: '';
        position: absolute;
        top: -40px;
        left: -40px;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(118, 196, 25, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-title {
        color: #151515;
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 6px;
    }
    .hero-subtitle {
        color: #2E5A1C;
        font-size: 14px;
        margin-bottom: 0;
        font-weight: 600;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #FFFFFF;
        color: #2E5A1C;
        border: 1px solid #D8F2B5;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
    }
    .hero-pulse {
        width: 8px;
        height: 8px;
        background-color: #76C419;
        border-radius: 50%;
        box-shadow: 0 0 0 4px rgba(118, 196, 25, 0.3);
        display: inline-block;
    }

    /* KPI Stat Cards */
    .kpi-card {
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        background: #FFFFFF;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(118, 196, 25, 0.12);
        border-color: #D8F2B5;
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: #76C419;
        border-radius: 4px 0 0 4px;
    }
    .kpi-card.kpi-revenue::before { background: linear-gradient(180deg, #76C419 0%, #87C826 100%); }
    .kpi-card.kpi-bookings::before { background: linear-gradient(180deg, #0284c7 0%, #38bdf8 100%); }
    .kpi-card.kpi-cars::before { background: linear-gradient(180deg, #7c3aed 0%, #a78bfa 100%); }
    .kpi-card.kpi-users::before { background: linear-gradient(180deg, #f59e0b 0%, #fbbf24 100%); }

    .kpi-icon-wrap {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .icon-revenue { background: #F5FDEA; color: #76C419; }
    .icon-bookings { background: #E0F2FE; color: #0284c7; }
    .icon-cars { background: #F3E8FF; color: #7c3aed; }
    .icon-users { background: #FEF3C7; color: #d97706; }

    .kpi-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .kpi-value {
        font-size: 26px;
        font-weight: 800;
        color: #151515;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .kpi-subtext {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Section Cards */
    .dashboard-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .dashboard-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dashboard-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #151515;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dashboard-card-title i {
        color: #76C419;
        font-size: 18px;
    }

    /* Progress Widget */
    .metric-progress-item {
        margin-bottom: 18px;
    }
    .metric-progress-item:last-child {
        margin-bottom: 0;
    }
    .metric-progress-header {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #334155;
    }
    .progress-custom {
        height: 8px;
        border-radius: 6px;
        background-color: #f1f5f9;
        overflow: hidden;
    }
    .progress-bar-figma {
        background: linear-gradient(90deg, #76C419 0%, #87C826 100%);
        border-radius: 6px;
    }

    /* Quick Action Pills */
    .quick-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
        text-decoration: none !important;
        margin-left: 10px !important;
        margin-bottom: 8px !important;
    }

    #carStatusDonutChart {
        margin-bottom: 24px !important;
        padding-bottom: 10px;
    }
    .quick-action-primary {
        background-color: #76C419;
        color: #FFFFFF !important;
        box-shadow: 0 3px 10px rgba(118, 196, 25, 0.3);
    }
    .quick-action-primary:hover {
        background-color: #63a913;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(118, 196, 25, 0.45);
    }
    .quick-action-light {
        background-color: #FFFFFF;
        color: #151515 !important;
        border: 1px solid #e2e8f0;
    }
    .quick-action-light:hover {
        background-color: #F5FDEA;
        border-color: #D8F2B5;
        color: #49672A !important;
        transform: translateY(-2px);
    }

    /* Table Enhancements */
    .table-dashboard th {
        font-size: 12.5px;
        letter-spacing: 0.5px;
        color: #ffffff !important;
        background: #1C3B1A !important;
        background-color: #1C3B1A !important;
        background-image: none !important;
        border-top: none !important;
        border-bottom: 2px solid #76C419 !important;
        padding: 13px 16px;
    }
    .table-dashboard td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #EDF4E5;
        font-size: 13px;
        color: #1E293B;
    }
    .table-dashboard tr:hover td {
        background-color: #F1FAE6;
    }
    .car-thumb-sm {
        width: 48px;
        height: 36px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    /* Badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }
    .status-badge.active { background-color: #dcfce7; color: #15803d; }
    .status-badge.completed { background-color: #F5FDEA; color: #49672A; border: 1px solid #D8F2B5; }
    .status-badge.pending { background-color: #fef9c3; color: #a16207; }
    .status-badge.cancelled { background-color: #fee2e2; color: #b91c1c; }

    /* Top Cars List */
    .car-rank-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .car-rank-item:last-child {
        border-bottom: none;
    }
    .car-rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #F5FDEA;
        color: #49672A;
        font-weight: 800;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #D8F2B5;
        flex-shrink: 0;
    }
    .car-rank-info {
        flex-grow: 1;
    }
    .car-rank-name {
        font-size: 13px;
        font-weight: 700;
        color: #151515;
        margin-bottom: 2px;
    }
    .car-rank-meta {
        font-size: 12px;
        color: #64748b;
    }
    .car-rank-count {
        font-weight: 800;
        font-size: 14px;
        color: #2E5A1C;
    }

    /* Interactive Chart Legends */
    .revenue-legend-btn, .donut-legend-btn {
        cursor: pointer;
        user-select: none;
        border: 1px solid transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        background: transparent;
        outline: none !important;
    }
    .revenue-legend-btn:hover, .donut-legend-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        filter: brightness(0.96);
    }
    .revenue-legend-btn:active, .donut-legend-btn:active {
        transform: translateY(0);
    }
    .revenue-legend-btn.is-collapsed, .donut-legend-btn.is-collapsed {
        opacity: 0.45 !important;
        background-color: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
        transform: none !important;
    }
    .revenue-legend-btn.is-collapsed span, .donut-legend-btn.is-collapsed span {
        color: #94a3b8 !important;
        text-decoration: line-through;
    }
    .revenue-legend-btn.is-collapsed .legend-indicator,
    .donut-legend-btn.is-collapsed span[class*="rounded-circle"] {
        background-color: #cbd5e1 !important;
        text-decoration: none !important;
    }
</style>
