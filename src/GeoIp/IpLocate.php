<?php

namespace Edisk\Common\GeoIp;

use Exception;
use InvalidArgumentException;
use MaxMind\Db\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;
use RuntimeException;

class IpLocate
{
    public function __construct(private ?string $databasePath = null)
    {
        if ($this->databasePath === null) {
            $this->databasePath = __DIR__ . '/Database/ip-to-country.mmdb';
        }

        if (!file_exists($this->databasePath)) {
            throw new InvalidArgumentException('Unable to read GeoIp database');
        }
    }

    /**
     * @throws InvalidDatabaseException
     */
    private function getGeoIpReader(): Reader
    {
        return new Reader($this->databasePath);
    }

    /**
     * The two-character ISO 3166-1 alpha code
     */
    public function getIpCountry(string $ip): ?string
    {
        try {
            $reader = $this->getGeoIpReader();
            $record = $reader->get($ip);
        } catch (Exception $e) {
            throw new RuntimeException('Unable to detect ip with GeoIp database', 0, $e);
        }

        return $record['country_code'] ?? null;
    }
}
