<?php

namespace Edisk\Common\Utils;

class StringHelper
{
    /** @noinspection PhpRedundantOptionalArgumentInspection */
    public static function fixUtf8(string $value): string
    {
        $encoding = mb_detect_encoding($value, mb_detect_order(), false);
        if ($encoding === 'UTF-8') {
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }

        return iconv(mb_detect_encoding($value, mb_detect_order(), false), 'UTF-8//IGNORE', $value);
    }
}
