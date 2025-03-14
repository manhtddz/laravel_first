<?php
namespace App\Const;

class Status
{
    public const LIST = [
        1 => 'On working',
        2 => 'Retired',
    ];

    public static function getName($id)
    {
        return self::LIST [$id] ?? "UNKNOWN";
    }
}