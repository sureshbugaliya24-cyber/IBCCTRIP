<?php
// backend/core/Currency.php

class Currency
{
    private static $pdo;
    private static $rates = [];
    private static $current = 'INR';
    private static $symbol = '₹';

    public static function init($pdo, $code)
    {
        self::$pdo = $pdo;
        self::$current = $code;

        $stmt = $pdo->prepare("SELECT code, symbol, exchange_rate FROM currencies");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            self::$rates[$row['code']] = [
                'symbol' => $row['symbol'],
                'rate' => $row['exchange_rate']
            ];
        }

        if (isset(self::$rates[$code])) {
            self::$symbol = self::$rates[$code]['symbol'];
        }
    }

    public static function convert($amountIdr)
    {
        $rate = self::$rates[self::$current]['rate'] ?? 1.0;
        return $amountIdr * $rate;
    }

    public static function format($amountIdr)
    {
        $converted = self::convert($amountIdr);
        return self::$symbol . number_format($converted, 2);
    }

    public static function getCode()
    {
        return self::$current;
    }
}
?>
