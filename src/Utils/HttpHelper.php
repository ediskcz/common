<?php

namespace Edisk\Common\Utils;

use RuntimeException;

class HttpHelper
{
    /**
     * @return array ['from' => $from, 'to' => $to, 'range' => $from . '-' . $to]
     */
    public static function parseRange(string $range, int $maxRange): array
    {
        $ranges = explode('-', str_ireplace('bytes=', '', $range));
        if (count($ranges) !== 2) {
            $ranges = explode('-', str_ireplace('bytes ', '', $range));
            if (count($ranges) !== 2) {
                throw new RuntimeException('Invalid range header');
            }
        }
        $from = 0;
        if (is_numeric($ranges[0])) {
            $from = (int) $ranges[0];
        }
        if (is_numeric($ranges[1])) {
            $to = (int) $ranges[1];
        } else {
            $to = $maxRange;
        }
        if ($from > $to) {
            throw new RuntimeException('Invalid range');
        }

        return ['from' => $from, 'to' => $to, 'range' => $from . '-' . $to];
    }
}
