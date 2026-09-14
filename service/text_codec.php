<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\service;

/**
 * Store arbitrary UTF-8 text as ASCII so database charset limitations cannot
 * corrupt 4-byte Unicode characters. Unprefixed values remain readable for
 * backward compatibility.
 */
class text_codec
{
    const PREFIX = 'phpbblab64:';

    public static function encode($value)
    {
        return self::PREFIX . base64_encode((string) $value);
    }

    public static function decode($value)
    {
        $value = (string) $value;
        $prefix_length = strlen(self::PREFIX);

        if (strncmp($value, self::PREFIX, $prefix_length) !== 0)
        {
            return $value;
        }

        $decoded = base64_decode(substr($value, $prefix_length), true);
        return $decoded === false ? $value : $decoded;
    }
}
