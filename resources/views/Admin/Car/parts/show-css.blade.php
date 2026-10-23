<style>
    /* Car Show / Profile Specific Styles */
    .car-hero-img {
        width: 100%;
        height: 380px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .car-thumb-img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #e2e8f0;
        transition: all 0.2s ease-in-out;
    }

    .car-thumb-img:hover,
    .car-thumb-img.active {
        border-color: #76C419;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(118, 196, 25, 0.35);
    }

    .spec-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .spec-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #F5FDEA;
        color: #234913;
        border: 1px solid #D8F2B5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
</style>
