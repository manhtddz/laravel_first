<?php
namespace App\Const;


class Gender
{
    public const LIST = [
        1 => 'Male',
        2 => 'Female',
    ];

    public static function getName($id)
    {
        return self::LIST [$id] ?? "UNKNOWN";
    }
}