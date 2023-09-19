<?php

namespace Edisk\Common\Utils;

class IpHelper
{
    /**
     * Checks if $string IP matches one in $ips array (either exact match or is in IP range IP1-IP2)
     * Supports IPv4
     */
    public static function isMatch(string $string, array $ips): bool
    {
        foreach ($ips as $ip) {
            if ($ip === $string) {
                return true;
            }
            $range = explode('-', $ip);
            if (count($range) === 2 &&
                filter_var(trim($range[0]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false &&
                filter_var(trim($range[1]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false
            ) {
                $ip_from = trim($range[0]);
                $ip_to = trim($range[1]);
                $ip_long = ip2long($string);
                if ($ip_long >= ip2long($ip_from) && $ip_long <= ip2long($ip_to)) {
                    return true;
                }
            }
        }

        return false;
    }
}
