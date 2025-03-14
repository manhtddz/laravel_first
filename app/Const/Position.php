<?php
namespace App\Const;

class Position
{
    public const LIST = [
        1 => 'Manager',
        2 => 'Team leader',
        3 => 'BSE',
        4 => 'DEV',
        5 => 'Tester',
    ];

    public static function getName($id)
    {
        return self::LIST [$id] ?? "UNKNOWN";
    }
}