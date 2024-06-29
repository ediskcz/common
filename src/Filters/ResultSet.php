<?php

namespace Edisk\Common\Filters;

class ResultSet
{
    public static function filterResultKeys(array $results, array $allowedKeys): array
    {
        foreach ($results as &$result) {
            $encode = json_encode($result, JSON_THROW_ON_ERROR);
            $result = json_decode($encode, true, 512, JSON_THROW_ON_ERROR);
            $result = array_filter(
                $result,
                static function ($key) use ($allowedKeys) {
                    return in_array($key, $allowedKeys, true);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return $results;
    }
}
