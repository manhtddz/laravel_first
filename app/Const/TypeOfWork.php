<?php
namespace App\Const;

class TypeOfWork
{
    public const LIST = [
        1 => 'Fulltime',
        2 => 'Partime',
        3 => 'Probationary Staff',
        4 => 'Intern',
    ];

    public static function getName($id)
    {
        return self::LIST [$id] ?? "UNKNOWN";
    }
}