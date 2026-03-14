<?php
// backend/config/env.php
if (!defined('IS_LIVE')) define('IS_LIVE', 0);

// Brand Identity
if (!defined('SITE_NAME_PART1')) define('SITE_NAME_PART1', 'IBCC');
if (!defined('SITE_NAME_PART2')) define('SITE_NAME_PART2', 'Trip');

// SVG Icon (Logo) - Centralized
if (!defined('SITE_ICON_SVG')) define('SITE_ICON_SVG', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>');

// Theme Colors
if (!defined('COLOR_PRIMARY'))   define('COLOR_PRIMARY',   '#0B3D91');
if (!defined('COLOR_SECONDARY')) define('COLOR_SECONDARY', '#FF6B00');
if (!defined('COLOR_ACCENT'))    define('COLOR_ACCENT',    '#F5F7FA');
if (!defined('COLOR_DARK'))      define('COLOR_DARK',      '#0A1628');

// Contact Information
if (!defined('WHATSAPP_NO'))    define('WHATSAPP_NO',    '917878335572');
if (!defined('CONTACT_EMAIL'))   define('CONTACT_EMAIL',   'info@ibcctrip.com');
if (!defined('CONTACT_PHONE'))   define('CONTACT_PHONE',   '+91 7878335572');
if (!defined('CONTACT_ADDRESS')) define('CONTACT_ADDRESS', '123, Travel Hub, Connaught Place, New Delhi - 110001');

// Social Links
if (!defined('FACEBOOK_URL'))  define('FACEBOOK_URL',  '#');
if (!defined('INSTAGRAM_URL')) define('INSTAGRAM_URL', '#');
if (!defined('TWITTER_URL'))   define('TWITTER_URL',   '#');
if (!defined('YOUTUBE_URL'))   define('YOUTUBE_URL',   '#');
