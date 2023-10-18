<?php

namespace Edisk\Common\Utils;

class UrlObfuscator
{
    public function obfuscateUrl(string $string): string
    {
        $string = $this->urlEncode($string);
        $string = $this->base64Encode($string);

        return $this->obfuscateSubstituteCharacters($string);
    }

    public function deobfuscateUrl(string $string): string
    {
        $string = $this->deobfuscateSubstituteCharacters($string);
        $string = $this->base64Decode($string);

        return $this->urlDecode($string);
    }

    private function urlEncode(string $string): string
    {
        return rawurlencode($string);
    }

    private function urlDecode(string $string): string
    {
        return rawurldecode($string);
    }

    private function base64Encode(string $string): string
    {
        return base64_encode($string);
    }

    private function base64Decode(string $string): string
    {
        return (string) base64_decode($string);
    }

    private function obfuscateSubstituteCharacters(string $string): string
    {
        $obfuscated = '';
        for ($i = 0, $iMax = strlen($string); $i < $iMax; $i++) {
            $obfuscated .= chr(ord($string[$i]) + 1);
        }

        return $obfuscated;
    }

    private function deobfuscateSubstituteCharacters(string $string): string
    {
        $deobfuscated = '';
        for ($i = 0, $iMax = strlen($string); $i < $iMax; $i++) {
            $deobfuscated .= chr(ord($string[$i]) - 1);
        }

        return $deobfuscated;
    }
}
