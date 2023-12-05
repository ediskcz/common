<?php

namespace Edisk\Common\Utils;

use Edisk\Common\GeoIp\GeoIpHelper;
use Symfony\Component\HttpFoundation\Request;

class CountryHelper
{
    public static function detectFromRequest(
        Request $request,
        array   $defaultByLang,
        array   $defaultByHost = []
    ): ?string {
        $detectedCountry = null;
        $availableLanguages = array_keys($defaultByLang);
        $browserLang = LocaleHelper::getBrowserLanguage($availableLanguages);
        if ($browserLang) {
            $detectedCountry = $defaultByLang[$browserLang] ?? null;
        }
        if (null === $detectedCountry) {
            $detectedCountry = $defaultByHost[$request->getHost()] ?? null;
        }

        // attempt to find in GeoIp
        $isoCode = GeoIpHelper::detectIpCountry($request->getClientIp());
        if (empty($isoCode)) {
            return $detectedCountry;
        }

        return strtolower($isoCode);
    }
}
