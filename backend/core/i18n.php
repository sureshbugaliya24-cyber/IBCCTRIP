<?php
// backend/core/i18n.php

class I18n
{
    private static $translations = [];
    private static $lang = 'en';

    public static function init($lang)
    {
        self::$lang = in_array($lang, ['en', 'hi']) ? $lang : 'en';
        // Path adjusted for frontend/locales separation
        $file = __DIR__ . "/../../frontend/locales/" . self::$lang . ".json";
        if (file_exists($file)) {
            $json = file_get_contents($file);
            self::$translations = json_decode($json, true);
        }
    }

    public static function get($key)
    {
        return self::$translations[$key] ?? $key;
    }

    public static function getLang()
    {
        return self::$lang;
    }
}
?>
