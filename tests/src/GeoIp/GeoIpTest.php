<?php

namespace Edisk\Common\GeoIp;

use PHPUnit\Framework\TestCase;

class GeoIpTest extends TestCase
{
    public function ipProvider(): array
    {
        $data = [];

        // Czech Republic (CZ) examples
        $data[] = ['31.30.0.0', 'CZ']; // Vodafone Czech Republic
        $data[] = ['31.169.128.0', 'CZ']; // Vodafone Czech Republic
        $data[] = ['62.77.64.0', 'CZ']; // T-Mobile Czech Republic

        $data[] = ['93.185.101.155', 'CZ']; // eDisk server
        $data[] = ['93.185.101.154', 'CZ']; // eDisk server
        $data[] = ['93.185.101.153', 'CZ']; // eDisk server
        $data[] = ['217.16.184.49', 'CZ']; // eDisk server
        $data[] = ['217.16.184.4', 'CZ']; // eDisk server
        $data[] = ['217.16.188.64', 'CZ']; // eDisk server

        // Slovakia (SK) examples
        $data[] = ['109.121.46.0', 'SK'];
        $data[] = ['109.238.202.0', 'SK'];
        $data[] = ['134.238.66.0', 'SK'];

        // Other countries for broader testing
        $data[] = ['8.8.8.8', 'US']; // Google DNS
        $data[] = ['103.21.244.0', 'US']; // Cloudflare
        $data[] = ['104.24.0.0', 'US']; // Cloudflare
        $data[] = ['91.66.192.0', 'DE']; // Germany

        return $data;
    }

    private IpLocate $geoIp;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $path = __DIR__ . '/../../../src/GeoIp/Database/ip-to-country.mmdb';
        $geoIp = new IpLocate($path);

        $this->geoIp = $geoIp;
    }

    /**
     * @dataProvider ipProvider
     */
    public function testDetectIpCountry($input, $expected): void
    {
        $this->assertEquals($expected, $this->geoIp->getIpCountry($input));
    }
}
