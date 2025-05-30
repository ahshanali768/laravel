<?php
namespace App\Helpers;

class PhoneMaskHelper
{
    /**
     * Mask a phone number in the format 123XXXX890
     */
    public static function mask($number)
    {
        if (!$number || strlen($number) < 7) return $number;
        return substr($number, 0, 3) . 'XXXX' . substr($number, -3);
    }

    /**
     * Mask a phone number in the format 123****90 (for DID)
     */
    public static function maskDid($number)
    {
        if (!$number || strlen($number) < 5) return $number;
        return substr($number, 0, 3) . '****' . substr($number, -2);
    }
}
