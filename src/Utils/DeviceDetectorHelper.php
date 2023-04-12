<?php

namespace Edisk\Common\Utils;

use DeviceDetector\DeviceDetector;

class DeviceDetectorHelper
{
    public static function getDeviceData(string $userAgent): array
    {
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        // keep keys order for serialization
        return [
            'os' => $dd->getOs(),
            'device' => $dd->getDeviceName(),
            'brand' => $dd->getBrandName(),
            'model' => $dd->getModel(),
            'client' => $dd->getClient(),
        ];
    }

    public static function getDeviceId(string $userAgent): string
    {
        $deviceData = self::getDeviceData($userAgent);
        $deviceIdKeys = ['os', 'device', 'brand', 'model'];
        $deviceValues = array_filter(
            $deviceData,
            static fn($key) => in_array($key, $deviceIdKeys, true),
            ARRAY_FILTER_USE_KEY
        );

        return md5(serialize($deviceValues));
    }
}
