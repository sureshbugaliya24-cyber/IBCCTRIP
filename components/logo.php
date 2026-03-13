<?php
/**
 * Logo Component
 * Returns the brand logo badge or full branding as HTML.
 */
function renderLogo($type = 'full', $classes = '') {
    $part1 = defined('SITE_NAME_PART1') ? SITE_NAME_PART1 : 'IBCC';
    $part2 = defined('SITE_NAME_PART2') ? SITE_NAME_PART2 : 'Trip';
    $svg   = defined('SITE_ICON_SVG')   ? SITE_ICON_SVG   : '';

    $iconHtml = '
    <div class="logo-icon w-9 h-9 rounded-xl bg-secondary flex items-center justify-center shadow-lg ' . $classes . '">
        <div class="w-5 h-5 text-white flex items-center justify-center">
            ' . $svg . '
        </div>
    </div>';

    if ($type === 'icon') return $iconHtml;

    $textHtml = '
    <span class="logo-text font-extrabold text-xl tracking-tight leading-none text-white">
        ' . $part1 . '&nbsp;<span class="text-secondary">' . $part2 . '</span>
    </span>';

    if ($type === 'text') return $textHtml;

    return '
    <div class="flex items-center gap-2 shrink-0">
        ' . $iconHtml . '
        ' . $textHtml . '
    </div>';
}
