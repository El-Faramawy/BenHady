<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting()
    {
        return Setting::first();
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin');
    }
}

if (!function_exists('user_api')) {
    function user_api()
    {
        return auth()->guard('user_api');
    }
}

if (!function_exists('get_file')) {
    function get_file($file)
    {
        $defaultImg = asset('Admin/imgs/default.jpg');

        if (empty($file) || (is_string($file) && trim($file) === '')) {
            return $defaultImg;
        }

        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $parsedPath = parse_url($file, PHP_URL_PATH);
            if ($parsedPath) {
                // If it points to local storage URL (e.g. http://localhost/storage/...)
                if (str_contains($parsedPath, '/storage/')) {
                    $storageRelative = substr($parsedPath, strpos($parsedPath, '/storage/') + 9);
                    if (
                        (file_exists(public_path('storage/' . $storageRelative)) && !is_dir(public_path('storage/' . $storageRelative))) ||
                        (file_exists(storage_path('app/public/' . $storageRelative)) && !is_dir(storage_path('app/public/' . $storageRelative)))
                    ) {
                        return $file;
                    }
                    return $defaultImg;
                }

                // If it points to local public file (e.g. Admin/imgs/default.jpg)
                $trimmedPublic = ltrim($parsedPath, '/');
                if (file_exists(public_path($trimmedPublic)) && !is_dir(public_path($trimmedPublic))) {
                    return $file;
                }
            }

            // Remote external URL
            return $file;
        }

        $file = ltrim((string) $file, '/');

        if (str_starts_with($file, 'storage/')) {
            $relativePath = substr($file, 8);
            if (file_exists(public_path($file)) && !is_dir(public_path($file))) {
                return asset($file);
            }
            if (file_exists(storage_path('app/public/' . $relativePath)) && !is_dir(storage_path('app/public/' . $relativePath))) {
                return asset($file);
            }
            return $defaultImg;
        }

        // If file exists directly in public/
        if (file_exists(public_path($file)) && !is_dir(public_path($file))) {
            return asset($file);
        }

        // If file exists in public/storage/ or storage/app/public/
        if (file_exists(public_path('storage/' . $file)) && !is_dir(public_path('storage/' . $file))) {
            return asset('storage/' . $file);
        }

        if (file_exists(storage_path('app/public/' . $file)) && !is_dir(storage_path('app/public/' . $file))) {
            return asset('storage/' . $file);
        }

        return $defaultImg;
    }
}

if (!function_exists('my_toaster')) {
    function my_toaster($message = 'تم بنجاح', $alert_type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('type', $alert_type);
    }
}

if (!function_exists('tableCheckbox')) {
    function tableCheckbox($id): string
    {
        return '<input type="checkbox" class="sub_chk" data-id="' . $id . '">';
    }
}

if (!function_exists('tableAction')) {
    function tableAction($id, $edit, $delete, $view = false)
    {
        $action = '';
        if ($edit) {
            $action .= '<button  id="editBtn" class="btn btn-default btn-primary btn-sm mb-2 mb-xl-0 " data-toggle="tooltip" title="تعديل"
                                data-id="' . $id . '" ><i class="fa fa-edit text-white"></i>
                            </button>';
        }
        if ($delete) {
            $action .= '<button class="btn btn-default btn-danger btn-sm mb-2 mb-xl-0 delete" data-toggle="tooltip" title="حذف"
                                data-id="' . $id . '" ><i class="fa fa-trash-o text-white"></i>
                            </button>';

        }
        if ($view) {
            $action .= '<button class="btn btn-default btn-action-view btn-sm mb-2 mb-xl-0 view" data-toggle="tooltip" title="عرض"
                                data-id="' . $id . '" ><i class="fa fa-eye text-white"></i>
                            </button>';

        }
        return $action;
    }
}

if (!function_exists('tableStatus')) {
    function tableStatus(bool $isActive): string
    {
        if ($isActive) {
            return '<span class="badge badge-success px-3 py-2" style="font-size: 11px; font-weight: 600; border-radius: 6px;"><i class="fa fa-check mr-1"></i> ' . __('messages.general.active') . '</span>';
        }
        return '<span class="badge badge-danger px-3 py-2" style="font-size: 11px; font-weight: 600; border-radius: 6px;"><i class="fa fa-times mr-1"></i> ' . __('messages.general.inactive') . '</span>';
    }
}

if (!function_exists('tableImage')) {
    function tableImage(?string $path, string $alt = 'thumbnail'): string
    {
        $defaultUrl = asset('Admin/imgs/default.jpg');
        $url = $path ? get_file($path) : $defaultUrl;
        return '<a href="' . htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8') . '" target="_blank" class="table-img-link" title="' . __('messages.general.view') . '">' .
            '<img src="' . htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8') . '" class="table-img shadow-sm" style="width: 60px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; margin: 0; padding: 0; display: block;" onerror="this.onerror=null;this.src=\'' . $defaultUrl . '\';" alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '">' .
            '</a>';
    }
}

if (!function_exists('tableIcon')) {
    function tableIcon(?string $icon, string $fallbackIcon = 'fa fa-sliders'): string
    {
        // 1. If empty or null, show fallback badge
        if (empty($icon)) {
            return '<div class="d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 44px; border-radius: 8px; background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; margin: 0; padding: 0;"><i class="' . $fallbackIcon . '" style="font-size: 19px;"></i></div>';
        }

        // 2. If it is a CSS font icon class (e.g. "fa fa-car" or "fe fe-settings")
        if (str_starts_with($icon, 'fa ') || str_starts_with($icon, 'fe ') || str_starts_with($icon, 'bi ') || str_starts_with($icon, 'mdi ')) {
            return '<div class="d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 44px; border-radius: 8px; background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; margin: 0; padding: 0;"><i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" style="font-size: 19px;"></i></div>';
        }

        // 3. If it is an inline raw SVG string
        if (str_starts_with(trim($icon), '<svg')) {
            return '<div class="table-img-link d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 44px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; margin: 0; padding: 0;">' . $icon . '</div>';
        }

        // 4. File path or URL
        $url = get_file($icon);
        $defaultUrl = asset('Admin/imgs/default.jpg');
        $isSvg = str_ends_with(strtolower((string) parse_url((string)$icon, PHP_URL_PATH)), '.svg');
        $fit = $isSvg ? 'contain' : 'cover';

        return '<a href="' . htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8') . '" target="_blank" class="table-img-link" title="' . __('messages.general.view') . '">' .
            '<img src="' . htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8') . '" class="table-img shadow-sm" style="width: 60px; height: 44px; object-fit: ' . $fit . '; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; margin: 0; padding: 0; display: block;" onerror="this.onerror=null;this.src=\'' . $defaultUrl . '\';" alt="icon">' .
            '</a>';
    }
}

if (!function_exists('featureIcon')) {
    function featureIcon(?string $icon, string $fallbackIcon = 'fa fa-check-square', int $size = 32): string
    {
        if (empty($icon)) {
            return '<div class="d-inline-flex align-items-center justify-content-center" style="width: ' . $size . 'px; height: ' . $size . 'px; border-radius: 6px; background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; flex-shrink: 0;"><i class="' . $fallbackIcon . '" style="font-size: 14px;"></i></div>';
        }

        if (str_starts_with($icon, 'fa ') || str_starts_with($icon, 'fe ') || str_starts_with($icon, 'bi ') || str_starts_with($icon, 'mdi ')) {
            return '<div class="d-inline-flex align-items-center justify-content-center" style="width: ' . $size . 'px; height: ' . $size . 'px; border-radius: 6px; background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; flex-shrink: 0;"><i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" style="font-size: 14px;"></i></div>';
        }

        if (str_starts_with(trim($icon), '<svg')) {
            return '<div class="d-inline-flex align-items-center justify-content-center" style="width: ' . $size . 'px; height: ' . $size . 'px; border-radius: 6px; background: #ffffff; border: 1px solid #e2e8f0; flex-shrink: 0; padding: 2px;">' . $icon . '</div>';
        }

        $url = get_file($icon);
        $defaultUrl = asset('Admin/imgs/default.jpg');
        $isSvg = str_ends_with(strtolower((string) parse_url((string)$icon, PHP_URL_PATH)), '.svg');
        $fit = $isSvg ? 'contain' : 'cover';

        return '<img src="' . htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8') . '" class="feature-img-static" style="width: ' . $size . 'px; height: ' . $size . 'px; object-fit: ' . $fit . '; border-radius: 6px; border: 1px solid #e2e8f0; background: #ffffff; padding: 2px; display: inline-block; flex-shrink: 0; pointer-events: none; transition: none !important; transform: none !important;" onerror="this.onerror=null;this.src=\'' . $defaultUrl . '\';" alt="feature icon">';
    }
}
