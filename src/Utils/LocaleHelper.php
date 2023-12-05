<?php

namespace Edisk\Common\Utils;

class LocaleHelper
{
    public static function getBrowserLanguage(array $availableLanguages = []): ?string
    {
        $languages = [];
        // e.g. "cs,en;q=0.9,en-GB;q=0.8,en-US;q=0.7,sk;q=0.6", "en-US,en;q=0.5"
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all(
                '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.\d+))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                $parsed
            );

            if (count($parsed[1])) {
                // create a list like "en" => 0.8
                $languages = array_combine($parsed[1], $parsed[4]);
                // set default to 1 for any without q factor
                foreach ($languages as $lang => $val) {
                    if ($val === '') {
                        $languages[$lang] = 1;
                    }
                }

                // sort list based on value
                arsort($languages, SORT_NUMERIC);
            }
        }

        if (empty($availableLanguages)) {
            return array_key_first($languages);
        }

        // look through sorted list and return first that we support in our languages
        foreach ($languages as $lang => $val) {
            $lang = substr($lang, 0, 2); // only lang from locale code
            if (in_array($lang, $availableLanguages, true)) {
                return $lang;
            }
        }

        return null;
    }
}
