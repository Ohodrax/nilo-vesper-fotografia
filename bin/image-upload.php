<?php
declare(strict_types=1);

function ini_size_bytes(string $value): int
{
    $value = trim($value);
    if ($value === '' || $value === '-1' || $value === '0') return 0;
    if (!preg_match('/\A([0-9]+)\s*([KMG]?)\z/i', $value, $matches)) return 0;
    return (int) $matches[1] * (['' => 1, 'K' => 1024, 'M' => 1048576, 'G' => 1073741824][strtoupper($matches[2])]);
}

function image_upload_limits(): array
{
    $bytes = 8 * 1024 * 1024;
    foreach (['upload_max_filesize', 'post_max_size'] as $setting) {
        $configured = ini_size_bytes((string) ini_get($setting));
        if ($configured > 0) $bytes = min($bytes, max(0, $configured - 65536));
    }
    $memory = ini_size_bytes((string) ini_get('memory_limit'));
    $pixels = $memory > 0 ? min(8000000, max(0, (int) floor(($memory - memory_get_usage(true) - 32 * 1024 * 1024) / 8))) : 8000000;
    return ['maxBytes' => $bytes, 'maxPixels' => $pixels, 'maxEdge' => 3200];
}
