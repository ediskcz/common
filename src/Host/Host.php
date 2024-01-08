<?php

namespace Edisk\Common\Host;

use RuntimeException;

class Host
{
    /**
     * Get current transfer rate in bytes
     */
    public static function getCurrentTransferRate(string $interface, int $delay = 5): array
    {
        $regex = '/RX .*? ?(bytes|bajtů):? ?(\d+) ?(.+?)TX .*? ?(bytes|bajtů):? ?(\d+)/ui';
        ob_start();
        passthru('/sbin/ifconfig ' . $interface);
        $string = trim(ob_get_clean());
        if (empty($string)) {
            throw new RuntimeException('Unable to get current transfer rate, check ifconfig command');
        }
        $string = preg_replace('/\s+/', ' ', $string);
        preg_match($regex, $string, $matches);
        $old = ['rx' => $matches[2], 'tx' => $matches[5]];
        unset($matches);
        sleep($delay);
        ob_start();
        passthru('/sbin/ifconfig ' . $interface);
        $string = trim(ob_get_clean());
        $string = preg_replace('/\s+/', ' ', $string);
        preg_match($regex, $string, $matches);
        $new = ['rx' => $matches[2], 'tx' => $matches[5]];
        $rx = 8 * ($new['rx'] - $old['rx']) / $delay;
        $tx = 8 * ($new['tx'] - $old['tx']) / $delay;

        return ['rx' => (float) $rx, 'tx' => (float) $tx];
    }

    public static function getFreeTransferRate(string $interface, float $available, int $delay = 5): array
    {
        $bps = self::getCurrentTransferRate($interface, $delay);
        $tx = round((($available / 2) - $bps['tx']) / 1000000);
        if ($tx < 0) {
            $tx = 0;
        }
        $rx = round((($available / 2) - $bps['rx']) / 1000000);
        if ($rx < 0) {
            $rx = 0;
        }

        return ['rx' => (float) $rx, 'tx' => (float) $tx];
    }

    public static function getLoad(): float
    {
        ob_start();
        passthru('uptime');
        $res = ob_get_clean();
        preg_match('/ load average: ([0-9.,]+), /', $res, $matches);
        if (empty($matches[1])) {
            throw new RuntimeException('Unable to get load, check uptime command');
        }

        return (float) str_replace(',', '.', $matches[1]);
    }

    public static function getFreeDiscSpace(string $filesystem): int
    {
        ob_start();
        passthru('df -B M');
        $res = ob_get_clean();
        $filesystem = str_replace('/', '\/', $filesystem);
        $regex = '/' . $filesystem . '([ \t]+)([0-9 ]+)M([ \t]+)(\d+)M([ \t]+)([0-9 ]+)M([ \t]+)(\d+)%([ \t]+)(.*)/';
        preg_match($regex, $res, $matches);
        if (empty($matches[6])) {
            throw new RuntimeException('Unable to get free disc space');
        }

        return (int) $matches[6];
    }
}
