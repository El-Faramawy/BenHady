<style>
    /* Catalog Specific Styles */
    .catalog-car-card {
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2eed3;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transition: all 0.25s ease-in-out;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .catalog-car-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(118, 196, 25, 0.18);
        border-color: #D8F2B5;
    }
    .catalog-car-img-box {
        position: relative;
        height: 200px;
        background: #f4f8ee;
        overflow: hidden;
    }
    .catalog-car-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .catalog-car-card:hover .catalog-car-img {
        transform: scale(1.05);
    }
    .catalog-brand-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.95);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .catalog-price-badge {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: linear-gradient(135deg, #1C3B1A 0%, #2A5424 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .spec-chip {
        background: #f8fafc;
        border: 1px solid #e2eed3;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 600;
        color: #2E5A1C;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>
