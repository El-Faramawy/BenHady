@extends('layouts.admin.auth.layout')
@section('content')

    {{-- Decorative floating shapes --}}
    <div class="login-shape login-shape-1"></div>
    <div class="login-shape login-shape-2"></div>
    <div class="login-shape login-shape-3"></div>

    <div class="login-card-wrapper">
        {{-- Logo --}}
        <div class="login-logo-wrap">
            <img src="{{get_file(setting()->logo)}}" class="login-logo-img" alt="{{setting()->name}}">
        </div>

        {{-- Card --}}
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-icon-badge">
                    <i class="fe fe-user"></i>
                </div>
                <h1 class="login-title">تسجيل الدخول</h1>
                <p class="login-subtitle">أدخل بياناتك للوصول إلى لوحة التحكم</p>
            </div>

            <form class="my_form login-form" action="{{route('admin.post_login')}}"
                  enctype="application/x-www-form-urlencoded" method="post" novalidate>
                @csrf

                {{-- Email Field --}}
                <div class="login-field-group">
                    <label class="login-field-label">البريد الإلكتروني</label>
                    <div class="login-field-wrap">
                        <span class="login-field-icon">
                            <i class="fa fa-envelope-o"></i>
                        </span>
                        <input class="login-field-input" type="email"
                               placeholder="example@domain.com" name="email" autocomplete="email" required>
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="login-field-group">
                    <label class="login-field-label">كلمة المرور</label>
                    <div class="login-field-wrap">
                        <span class="login-field-icon">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input class="login-field-input" type="password"
                               placeholder="••••••••" name="password" autocomplete="current-password" required>
                        <button type="button" class="login-toggle-pass" tabindex="-1" onclick="togglePassword(this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-submit-btn">
                    <span class="login-btn-text">دخول إلى النظام</span>
                    <span class="login-btn-icon"><i class="fa fa-sign-in"></i></span>
                </button>
            </form>
        </div>

        <p class="login-footer-text">{{setting()->name}} &copy; {{date('Y')}}</p>
    </div>

    <style>
        /* ===== LOGIN PAGE CUSTOM STYLES ===== */

        body.app {
            overflow-x: hidden;
            min-height: 100vh;
        }

        .login-img {
            background: linear-gradient(145deg, #0d2b0a 0%, #1a4a12 35%, #2e6e1e 65%, #3d8a28 100%) !important;
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            box-sizing: border-box;
        }

        .page {
            min-height: auto !important;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* Floating decorative shapes */
        .login-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .login-shape-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(118,196,25,0.18) 0%, transparent 70%);
            top: -150px; right: -100px;
            animation: floatShape 8s ease-in-out infinite;
        }
        .login-shape-2 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(62,140,14,0.15) 0%, transparent 70%);
            bottom: -80px; left: -60px;
            animation: floatShape 10s ease-in-out infinite reverse;
        }
        .login-shape-3 {
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(180,255,80,0.1) 0%, transparent 70%);
            top: 40%; left: 25%;
            animation: floatShape 12s ease-in-out infinite 2s;
        }
        @keyframes floatShape {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* ===== CARD WRAPPER ===== */
        .login-card-wrapper {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 580px;
            padding: 10px 0;
            margin: 0 auto;
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== LOGO ===== */
        .login-logo-wrap {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-logo-img {
            max-height: 70px;
            max-width: 200px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0,0,0,0.4)) brightness(1.05);
        }

        /* ===== CARD ===== */
        .login-card {
            width: 100%;
            background: rgba(255,255,255,0.97);
            border-radius: 26px;
            padding: 44px 48px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            box-sizing: border-box;
        }

        /* ===== CARD HEADER ===== */
        .login-card-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #76C419 0%, #3d8a10 100%);
            border-radius: 18px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(118,196,25,0.45);
        }
        .login-icon-badge i {
            font-size: 26px;
            color: #ffffff;
        }
        .login-title {
            font-size: 26px !important;
            font-weight: 800 !important;
            color: #1a3a10 !important;
            margin: 0 0 6px 0 !important;
            letter-spacing: -0.3px;
        }
        .login-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }

        /* ===== FORM FIELDS ===== */
        .login-form { width: 100%; }

        .login-field-group {
            margin-bottom: 20px;
        }
        .login-field-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
            padding-right: 2px;
        }
        .login-field-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .login-field-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #76C419;
            font-size: 16px;
            pointer-events: none;
            z-index: 2;
        }
        .login-field-icon i { color: #76C419; }

        .login-field-input {
            width: 100%;
            height: 50px;
            padding: 0 46px 0 44px;
            background: #f8fdf4;
            border: 1.5px solid #d1e8b0;
            border-radius: 12px;
            font-size: 14px;
            color: #1a3a10;
            font-family: 'Cairo', sans-serif !important;
            transition: all 0.25s ease;
            direction: ltr;
            text-align: right;
        }
        .login-field-input::placeholder {
            color: #94a3b8;
            font-family: 'Cairo', sans-serif !important;
            direction: ltr;
            text-align: right;
        }
        .login-field-input:focus {
            border-color: #76C419;
            background: #ffffff;
            box-shadow: 0 0 0 3.5px rgba(118,196,25,0.2);
            outline: none;
        }

        /* Toggle password button */
        .login-toggle-pass {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            padding: 4px;
            z-index: 2;
            transition: color 0.2s;
        }
        .login-toggle-pass:hover { color: #76C419; }

        /* ===== SUBMIT BUTTON ===== */
        .login-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #76C419 0%, #3d8a10 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            font-family: 'Cairo', sans-serif !important;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 6px 20px rgba(118,196,25,0.45);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .login-submit-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .login-submit-btn:hover::before { left: 100%; }
        .login-submit-btn:hover {
            background: linear-gradient(135deg, #68b015 0%, #2d6a0a 100%);
            box-shadow: 0 8px 28px rgba(118,196,25,0.55);
            transform: translateY(-2px);
        }
        .login-submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(118,196,25,0.35);
        }
        .login-btn-icon i { font-size: 18px; }

        /* ===== FOOTER ===== */
        .login-footer-text {
            margin-top: 18px;
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            text-align: center;
            font-weight: 500;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .login-card-wrapper {
                max-width: 100%;
                padding: 10px 0;
            }
            .login-card {
                padding: 36px 26px;
                border-radius: 20px;
            }
            .login-title {
                font-size: 23px !important;
            }
            .login-icon-badge {
                width: 56px;
                height: 56px;
                border-radius: 16px;
            }
            .login-icon-badge i {
                font-size: 22px;
            }
        }

        @media (max-width: 440px) {
            .login-img {
                padding: 16px 10px;
            }
            .login-card {
                padding: 28px 18px;
                border-radius: 16px;
            }
            .login-card-header {
                margin-bottom: 24px;
            }
            .login-title {
                font-size: 21px !important;
            }
            .login-subtitle {
                font-size: 12.5px;
            }
            .login-field-input {
                height: 46px;
                font-size: 13.5px;
            }
            .login-submit-btn {
                height: 48px;
                font-size: 15px;
            }
        }
    </style>

    <script>
        function togglePassword(btn) {
            var input = btn.closest('.login-field-wrap').querySelector('.login-field-input');
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }
    </script>
@endsection
