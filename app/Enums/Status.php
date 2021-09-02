<?php

namespace App\Enums;

use DNC\Enum\Type\StringEnum;

class Status extends StringEnum
{
    public const ACTIVE = 'Active';
    public const INACTIVE = 'Inactive';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return [
            'Active' => static::ACTIVE,
            'Inactive' => static::INACTIVE,
        ];
    }
}
