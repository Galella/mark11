<?php

namespace App\Services;

class ISO6346Validator
{
    /**
     * Validate an ISO 6346 container number
     * 
     * The ISO 6346 standard specifies that:
     * - First 4 characters are alphabetic (owner code + equipment category)
     * - Next 6 characters are numeric (serial number)
     * - Last character is a check digit (numeric)
     * 
     * The check digit is calculated using a specific algorithm
     */
    public static function validateContainerNumber(string $containerNumber): bool
    {
        // Check if the format matches the standard (4 letters + 6 digits + 1 check digit)
        if (!preg_match('/^[A-Z]{4}\d{7}$/', $containerNumber)) {
            return false;
        }

        // Extract the first 10 characters (without the check digit)
        $baseNumber = substr($containerNumber, 0, 10);
        $providedCheckDigit = intval($containerNumber[10]);

        // Calculate the expected check digit
        $calculatedCheckDigit = self::calculateCheckDigit($baseNumber);

        // Compare the calculated check digit with the provided one
        return $calculatedCheckDigit === $providedCheckDigit;
    }

    /**
     * Calculate the ISO 6346 check digit for a container number
     */
    private static function calculateCheckDigit(string $baseNumber): int
    {
        $sum = 0;
        $multipliers = [1, 2, 4, 8, 5, 10, 9, 7, 3, 6]; // Powers of 2 modulo 11, with special handling
        
        for ($i = 0; $i < 10; $i++) {
            $char = $baseNumber[$i];
            $value = self::getCharacterValue($char);
            $sum += $value * $multipliers[$i];
        }
        
        $checkDigit = $sum % 11;
        
        // If the check digit is 10, it becomes 0
        return ($checkDigit == 10) ? 0 : $checkDigit;
    }

    /**
     * Get the numeric value of a character according to ISO 6346
     * A=10, B=12, C=13, ..., Z=38
     * 0=0, 1=1, 2=2, ..., 9=9
     */
    private static function getCharacterValue(string $char): int
    {
        if (ctype_digit($char)) {
            return intval($char);
        } elseif (ctype_alpha($char)) {
            // For ISO 6346, letters have specific values starting from A=10
            // A=10, B=12, C=13, D=14, E=15, F=16, G=17, H=18, I=19, J=20, K=21, L=23, M=24
            // N=25, O=26, P=27, Q=28, R=29, S=30, T=31, U=32, V=33, W=34, X=35, Y=36, Z=37
            $letterValues = [
                'A' => 10, 'B' => 12, 'C' => 13, 'D' => 14, 'E' => 15,
                'F' => 16, 'G' => 17, 'H' => 18, 'I' => 19, 'J' => 20,
                'K' => 21, 'L' => 23, 'M' => 24, 'N' => 25, 'O' => 26,
                'P' => 27, 'Q' => 28, 'R' => 29, 'S' => 30, 'T' => 31,
                'U' => 32, 'V' => 33, 'W' => 34, 'X' => 35, 'Y' => 36, 'Z' => 37
            ];
            
            $char = strtoupper($char);
            return $letterValues[$char] ?? 0;
        }
        
        return 0;
    }

    /**
     * Generate a valid check digit for a container number
     */
    public static function generateCheckDigit(string $baseNumber): int
    {
        if (strlen($baseNumber) !== 10) {
            throw new \InvalidArgumentException('Base number must be exactly 10 characters');
        }

        $sum = 0;
        $multipliers = [1, 2, 4, 8, 5, 10, 9, 7, 3, 6];
        
        for ($i = 0; $i < 10; $i++) {
            $char = $baseNumber[$i];
            $value = self::getCharacterValue($char);
            $sum += $value * $multipliers[$i];
        }
        
        $checkDigit = $sum % 11;
        return ($checkDigit == 10) ? 0 : $checkDigit;
    }
}