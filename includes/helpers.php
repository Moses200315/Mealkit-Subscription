<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function pe(mixed $value): void
{
    echo e($value);
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return $path === '' ? APP_URL . '/' : APP_URL . '/' . $path;
}

function asset(string $path): string
{
    return ASSETS_URL . '/' . ltrim($path, '/');
}

function upload_url(string $path): string
{
    return UPLOADS_URL . '/' . ltrim($path, '/');
}

/**
 * Build the URL for a recipe image.
 * Supports Cloudinary full URLs, local uploads, and fallbacks.
 */
function recipe_img_url(?string $filename): string
{
    if ($filename) {
        // 1. Kama filename ni URL kamili ya Cloudinary (https://res.cloudinary.com/...)
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        // 2. Local uploads directory
        $uploadPath = RECIPE_IMG_PATH . DS . $filename;
        if (file_exists($uploadPath)) {
            return RECIPE_IMG_URL . '/' . $filename;
        }

        // 3. Fallback to assets directory
        $assetPath = ASSETS_PATH . DS . 'images' . DS . $filename;
        if (file_exists($assetPath)) {
            return ASSETS_URL . '/images/' . $filename;
        }
    }
    return ASSETS_URL . '/images/' . DEFAULT_RECIPE_IMG;
}

/**
 * Build the URL for a user avatar, falling back to the default placeholder.
 */
function avatar_url(?string $filename): string
{
    if ($filename) {
        // Kama ni URL kamili ya Cloudinary
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        if ($filename !== DEFAULT_AVATAR && file_exists(PROFILE_IMG_PATH . DS . $filename)) {
            return PROFILE_IMG_URL . '/' . $filename;
        }
    }
    return ASSETS_URL . '/images/default-avatar.png';
}

// ══════════════════════════════════════════════════════════════════════════════
// REDIRECTION
// ══════════════════════════════════════════════════════════════════════════════

function redirect(string $url, int $statusCode = 302): never
{
    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
        $url = APP_URL . '/' . ltrim($url, '/');
    }
    header('Location: ' . $url, true, $statusCode);
    exit;
}

function redirect_back(string $fallback = '/'): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    redirect($referer ?: $fallback);
}

// ══════════════════════════════════════════════════════════════════════════════
// FLASH MESSAGES
// ══════════════════════════════════════════════════════════════════════════════

function flash(string $type, string $message): void
{
    Session::setFlash($type, $message);
}

function render_flash(): string
{
    $typeMap = [
        'success' => 'success',
        'error'   => 'danger',
        'warning' => 'warning',
        'info'    => 'info',
    ];

    $icons = [
        'success' => '✅',
        'error'   => '❌',
        'warning' => '⚠️',
        'info'    => 'ℹ️',
    ];

    $html = '';
    foreach ($typeMap as $flashType => $bsType) {
        $messages = Session::getFlash($flashType);
        foreach ($messages as $msg) {
            $icon = $icons[$flashType] ?? '';
            $html .= '<div class="alert alert-' . $bsType . ' alert-dismissible fade show" role="alert">';
            $html .= '<span class="me-2">' . $icon . '</span>' . e($msg);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            $html .= '</div>';
        }
    }
    return $html;
}

// ══════════════════════════════════════════════════════════════════════════════
// OLD FORM INPUT
// ══════════════════════════════════════════════════════════════════════════════

function old(string $key, string $default = ''): string
{
    return Session::getOldInput($key, $default);
}

// ══════════════════════════════════════════════════════════════════════════════
// AUTHENTICATION SHORTCUTS
// ══════════════════════════════════════════════════════════════════════════════

function is_logged_in(): bool
{
    return Session::isLoggedIn();
}

function is_customer(): bool
{
    return Session::isCustomer();
}

function is_admin(): bool
{
    return Session::isAdmin();
}

function current_user(): ?array
{
    return Session::user();
}

function current_user_id(): ?int
{
    return Session::userId();
}

// ══════════════════════════════════════════════════════════════════════════════
// CSRF
// ══════════════════════════════════════════════════════════════════════════════

function csrf_field(): string
{
    return Security::csrfField();
}

function csrf_token(): string
{
    return Security::generateCSRFToken();
}

// ══════════════════════════════════════════════════════════════════════════════
// STRING UTILITIES
// ══════════════════════════════════════════════════════════════════════════════

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
    $text = preg_replace('/[\s\-]+/', '-', $text);
    return trim($text, '-');
}

function truncate(string $text, int $limit = 120, string $suffix = '…'): string
{
    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $limit, 'UTF-8')) . $suffix;
}

function format_duration(int $minutes): string
{
    if ($minutes <= 0) {
        return '0 min';
    }
    $hours = intdiv($minutes, 60);
    $mins  = $minutes % 60;
    $parts = [];
    if ($hours > 0) {
        $parts[] = $hours . ' hr';
    }
    if ($mins > 0) {
        $parts[] = $mins . ' min';
    }
    return implode(' ', $parts);
}

function ordinal(int $n): string
{
    $suffix = ['th','st','nd','rd'];
    $v      = $n % 100;
    return $n . ($suffix[($v - 20) % 10] ?? $suffix[$v] ?? $suffix[0]);
}

// ══════════════════════════════════════════════════════════════════════════════
// DATE & TIME
// ══════════════════════════════════════════════════════════════════════════════

function format_date(?string $datetime, string $format = 'd M Y'): string
{
    if (empty($datetime)) {
        return 'N/A';
    }
    try {
        return (new DateTime($datetime))->format($format);
    } catch (Exception) {
        return 'Invalid date';
    }
}

function time_ago(string $datetime): string
{
    try {
        $time  = (new DateTime($datetime))->getTimestamp();
        $diff  = time() - $time;

        return match (true) {
            $diff < 60                => 'Just now',
            $diff < 3600              => intdiv($diff, 60)     . ' minute'  . (intdiv($diff, 60)     !== 1 ? 's' : '') . ' ago',
            $diff < 86400             => intdiv($diff, 3600)   . ' hour'    . (intdiv($diff, 3600)   !== 1 ? 's' : '') . ' ago',
            $diff < 604800            => intdiv($diff, 86400)  . ' day'     . (intdiv($diff, 86400)  !== 1 ? 's' : '') . ' ago',
            $diff < 2592000           => intdiv($diff, 604800) . ' week'    . (intdiv($diff, 604800) !== 1 ? 's' : '') . ' ago',
            $diff < 31536000          => intdiv($diff, 2592000). ' month'   . (intdiv($diff, 2592000)!== 1 ? 's' : '') . ' ago',
            default                   => intdiv($diff, 31536000). ' year'   . (intdiv($diff, 31536000)!== 1 ? 's' : '') . ' ago',
        };
    } catch (Exception) {
        return 'Unknown time';
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// CURRENCY & NUMBERS
// ══════════════════════════════════════════════════════════════════════════════

function format_currency($amount, $symbol = 'TSH') {
    $symbol = is_string($symbol) ? $symbol : 'TSH';
    return $symbol . ' ' . number_format((float)$amount, 2);
}

function format_file_size(int $bytes): string
{
    if ($bytes < 1024)       return $bytes       . ' B';
    if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
    if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
    return round($bytes / 1073741824, 2) . ' GB';
}

// ══════════════════════════════════════════════════════════════════════════════
// FILE UPLOADS (CLOUDINARY INTEGRATION)
// ══════════════════════════════════════════════════════════════════════════════

/**
 * Core function to upload file directly to Cloudinary.
 */
function uploadToCloudinary(string $fileTmpPath): string|false
{
    $cloudName = 's04ipenq'; 
    $uploadPreset = 'jvjz2p7n';

    $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

    $postFields = [
        'file' => new CURLFile($fileTmpPath),
        'upload_preset' => $uploadPreset
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode((string)$response, true);

    if (isset($responseData['secure_url'])) {
        return $responseData['secure_url'];
    }

    return false;
}

/**
 * Handles image upload via Cloudinary.
 */
function upload_image(array $file, string $directory = '', ?string $oldFilename = null): array
{
    // 1. Validate file
    $validation = Security::validateImageUpload($file);
    if (!$validation['valid']) {
        return ['success' => false, 'filename' => '', 'error' => $validation['error']];
    }

    // 2. Upload to Cloudinary
    $cloudinaryUrl = uploadToCloudinary($file['tmp_name']);

    if ($cloudinaryUrl) {
        return ['success' => true, 'filename' => $cloudinaryUrl, 'error' => ''];
    }

    return ['success' => false, 'filename' => '', 'error' => 'Failed to upload image to Cloudinary.'];
}

function delete_uploaded_file(string $directory, string $filename): bool
{
    return true; // Images stored in Cloudinary are protected
}

// ══════════════════════════════════════════════════════════════════════════════
// PAGINATION & VIEWS
// ══════════════════════════════════════════════════════════════════════════════

function paginate(int $totalItems, int $perPage, int $currentPage, string $baseUrl = ''): array
{
    $perPage     = max(1, $perPage);
    $totalPages  = (int) ceil($totalItems / $perPage);
    $currentPage = max(1, min($currentPage, max(1, $totalPages)));
    $offset      = ($currentPage - 1) * $perPage;

    $range   = 2;
    $start   = max(1, $currentPage - $range);
    $end     = min($totalPages, $currentPage + $range);
    $pages   = range($start, $end);

    return [
        'total_items'  => $totalItems,
        'per_page'     => $perPage,
        'current_page' => $currentPage,
        'total_pages'  => $totalPages,
        'has_prev'     => $currentPage > 1,
        'has_next'     => $currentPage < $totalPages,
        'prev_page'    => $currentPage - 1,
        'next_page'    => $currentPage + 1,
        'offset'       => $offset,
        'pages'        => $pages,
        'base_url'     => $baseUrl,
    ];
}

function render_pagination(array $pager): string
{
    if ($pager['total_pages'] <= 1) {
        return '';
    }

    $base = rtrim($pager['base_url'], '?&');
    $sep  = str_contains($base, '?') ? '&' : '?';

    $html  = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center flex-wrap">';

    if ($pager['has_prev']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . e($base . $sep . 'page=' . $pager['prev_page']) . '">&laquo; Prev</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>';
    }

    if ($pager['pages'][0] > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . e($base . $sep . 'page=1') . '">1</a></li>';
        if ($pager['pages'][0] > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
    }

    foreach ($pager['pages'] as $page) {
        $active = ($page === $pager['current_page']) ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . e($base . $sep . 'page=' . $page) . '">' . $page . '</a></li>';
    }

    $lastVisible = end($pager['pages']);
    if ($lastVisible < $pager['total_pages']) {
        if ($lastVisible < $pager['total_pages'] - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . e($base . $sep . 'page=' . $pager['total_pages']) . '">' . $pager['total_pages'] . '</a></li>';
    }

    if ($pager['has_next']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . e($base . $sep . 'page=' . $pager['next_page']) . '">Next &raquo;</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

function active_class(string $path, string $class = 'active'): string
{
    $current = $_SERVER['REQUEST_URI'] ?? '';
    return str_contains($current, $path) ? $class : '';
}

function difficulty_badge(string $difficulty): string
{
    $map = unserialize(DIFFICULTY_LABELS);
    $d   = $map[$difficulty] ?? ['label' => ucfirst($difficulty), 'class' => 'secondary'];
    return '<span class="badge bg-' . $d['class'] . '">' . e($d['label']) . '</span>';
}

function status_badge(string $status): string
{
    $map = [
        'active'    => 'success',
        'success'   => 'success',
        'pending'   => 'warning',
        'expired'   => 'secondary',
        'cancelled' => 'secondary',
        'failed'    => 'danger',
        'refunded'  => 'info',
        'draft'     => 'secondary',
        'published' => 'success',
        'archived'  => 'dark',
        'inactive'  => 'secondary',
        'banned'    => 'danger',
    ];
    $class = $map[strtolower($status)] ?? 'secondary';
    return '<span class="badge bg-' . $class . '">' . e(ucfirst($status)) . '</span>';
}

function scale_quantity(string $quantity, int $originalServings, int $newServings): string
{
    if ($originalServings <= 0 || $newServings <= 0) {
        return $quantity;
    }

    $factor = $newServings / $originalServings;

    if (preg_match('/^(\d+)\s*\/\s*(\d+)$/', trim($quantity), $m)) {
        $numeric = (float) $m[1] / (float) $m[2];
        return format_quantity($numeric * $factor);
    }

    if (preg_match('/^(\d+)\s+(\d+)\s*\/\s*(\d+)$/', trim($quantity), $m)) {
        $numeric = (float) $m[1] + (float) $m[2] / (float) $m[3];
        return format_quantity($numeric * $factor);
    }

    if (is_numeric(trim($quantity))) {
        return format_quantity((float) $quantity * $factor);
    }

    return $quantity;
}

function format_quantity(float $value): string
{
    $fractions = [
        '0.125' => '1/8', '0.25' => '1/4', '0.333' => '1/3',
        '0.375' => '3/8', '0.5'  => '1/2', '0.625' => '5/8',
        '0.667' => '2/3', '0.75' => '3/4', '0.875' => '7/8',
    ];

    $whole    = (int) floor($value);
    $decimal  = round($value - $whole, 3);

    $fracStr = '';
    foreach ($fractions as $fracStrKey => $label) {
        $fracVal = (float) $fracStrKey;
        if (abs($decimal - $fracVal) < 0.01) {
            $fracStr = $label;
            break;
        }
    }

    if ($whole > 0 && $fracStr !== '') {
        return $whole . ' ' . $fracStr;
    }
    if ($whole === 0 && $fracStr !== '') {
        return $fracStr;
    }
    if ($decimal === 0.0 || abs($decimal) < 0.01) {
        return (string) $whole;
    }
    return rtrim(rtrim(number_format($value, 2), '0'), '.');
}

function generate_unique_slug(string $title, string $table, string $column = 'slug', int $excludeId = 0): string
{
    $db      = Database::getInstance();
    $base    = slugify($title);
    $slug    = $base;
    $counter = 2;

    while (true) {
        $sql    = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = ?";
        $params = [$slug];

        if ($excludeId > 0) {
            $sql      .= ' AND `id` != ?';
            $params[]  = $excludeId;
        }

        $count = (int) $db->fetchColumn($sql, $params);
        if ($count === 0) {
            break;
        }
        $slug = $base . '-' . $counter++;
    }

    return $slug;
}

function render_view(string $viewPath, array $data = []): void
{
    $file = VIEWS_PATH . DS . str_replace('/', DS, $viewPath) . '.php';
    if (!file_exists($file)) {
        if (APP_DEBUG) {
            die("View not found: {$file}");
        }
        http_response_code(404);
        include VIEWS_PATH . DS . 'layouts' . DS . '404.php';
        exit;
    }
    extract($data, EXTR_SKIP);
    require $file;
}

function dd(mixed ...$vars): never
{
    if (APP_DEBUG) {
        echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:1rem;border-radius:6px;font-size:.85rem;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
    }
    exit;
}

function dump(mixed ...$vars): void
{
    if (!APP_DEBUG) {
        return;
    }
    echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:1rem;border-radius:6px;font-size:.85rem;">';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
}
