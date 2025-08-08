<?php

namespace app\components;

class TextHelper
{
    public static function pluralForm(int $count, array $forms): string
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $count." ".$forms[ ($count%100 > 4 && $count %100 < 20) ? 2 : $cases[min($count%10, 5)] ];
    }

    public static function hideIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                return "{$parts[0]}.{$parts[1]}.*.*";
            }
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            if (count($parts) >= 4) {
                $lastParts = array_slice($parts, -4);
                $maskedPart = array_fill(0, 4, '****');
                array_splice($parts, -4, 4, $maskedPart);

                return implode(':', $parts);
            }
        }

        return $ip;
    }
}