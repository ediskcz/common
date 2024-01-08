<?php

namespace Edisk\Common\Host;

use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HostTest extends TestCase
{
    public function testGetCurrentTransferRate(): void
    {
        $result = Host::getCurrentTransferRate('eth0', 1);
        self::assertIsArray($result);
        self::assertArrayHasKey('rx', $result);
        self::assertArrayHasKey('tx', $result);
        self::assertIsFloat($result['rx']);
        self::assertIsFloat($result['tx']);
    }

    public function testGetFreeTransferRate(): void
    {
        $result = Host::getFreeTransferRate('eth0', 1000000000, 1);
        self::assertIsArray($result);
        self::assertArrayHasKey('rx', $result);
        self::assertArrayHasKey('tx', $result);
        self::assertIsFloat($result['rx']);
        self::assertIsFloat($result['tx']);
    }

    public function testGetLoad(): void
    {
        $result = Host::getLoad();
        self::assertIsFloat($result);
    }

    public function testGetFreeDiscSpace(): void
    {
        ob_start();
        passthru('df -B M');
        $res = ob_get_clean();
        $discs = explode(PHP_EOL, $res);
        $first = $discs[1] ?? null;
        if (!$first) {
            throw new RuntimeException('Unable to find disc to test');
        }
        $filesystem = explode(' ', $first)[0];
        $result = Host::getFreeDiscSpace($filesystem);
        self::assertGreaterThanOrEqual(0, $result);
    }
}
