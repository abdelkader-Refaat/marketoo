<?php

namespace App\Traits;


trait EnumRetriever
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromCase($case)
    {
        $name_is_exist = array_search(strtolower($case), array_map('strtolower', self::names()));
        $case = $name_is_exist != false ? self::names()[$name_is_exist] : self::names()[0];
        return constant("self::$case")->value;
    }

    public static function getName($value): string
    {
        $constants = self::cases();
        foreach ($constants as $constant) {
            if ($constant->value === $value) {
                return $constant->name;
            }
        }
        return self::cases()[0]->name;
    }
}
