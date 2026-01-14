<?php

namespace Edisk\Common\GeoIp;

use PHPUnit\Framework\TestCase;

class GeoIpHelperTest extends TestCase
{
    public function ipProvider(): array
    {
        $data = [];
        $data[] = ['31.30.0.0', 'CZ']; // Vodafone Czech Republic
        $data[] = ['31.169.128.0', 'CZ']; // Vodafone Czech Republic
        $data[] = ['62.77.64.0', 'CZ']; // T-Mobile Czech Republic

        $data[] = ['93.185.101.155', 'CZ']; // eDisk server
        $data[] = ['93.185.101.154', 'CZ']; // eDisk server
        $data[] = ['93.185.101.153', 'CZ']; // eDisk server
        $data[] = ['217.16.184.49', 'CZ']; // eDisk server
        $data[] = ['217.16.184.4', 'CZ']; // eDisk server
        $data[] = ['217.16.188.64', 'CZ']; // eDisk server

        return $data;
    }


    /**
     * @dataProvider ipProvider
     */
    public function testDetectIpCountry($input, $expected): void
    {
        $this->assertEquals($expected, GeoIpHelper::detectIpCountry($input));
    }
}
