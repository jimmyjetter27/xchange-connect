<?php

namespace KorbaXchange;

class KorbaHelper
{
    public static function codeToNetwork($code) {
        if ($code == "01") {
            return "MTN";
        } else if ($code == "02") {
            return "VOD";
        } else if ($code == "06") {
            return "AIR";
        } else if ($code == "03") {
            return "TIG";
        } else {
            return null;
        }
    }

    /**
     * Util public static function random.
     * It generates random ids
     * @return string
     */
    public static function random() {
        return rand(1000, 10000).rand(1000, 10000).rand(1000, 10000).rand(1000, 10000);
    }


    /**
     * Util public static function numberGHFormat.
     * It convert phone numbers to Ghana Phone Number Format
     * @param string $int_number Number to convert
     * @return string
     */
    public static function numberGHFormat($int_number) {
        if (preg_match('/^\+233/', $int_number)) {
            return preg_replace("/^\+233/", "0", $int_number);
        }
        return preg_replace('/^233/', '0', $int_number);
    }


    /**
     * Util public static function numberIntFormat.
     * It convert phone numbers to the Internation Number Format
     * @param string $gh_number Number to convert
     * @return string
     */
    public static function numberIntFormat($gh_number) {
        if (preg_match('/^0/', $gh_number)) {
            return preg_replace('/^0/', '+233', $gh_number);
        }
        return preg_replace('/^233/', '+233', $gh_number);
    }


    /**
     * Util public static function number233Format.
     * It convert phone numbers to the 233 Number Format
     * @param string $number Number to convert
     * @return string
     */
    public static function number233Format($number) {
        if (preg_match('/^0/', $number)) {
            return preg_replace('/^0/', '233', $number);
        }
        return preg_replace('/^\+233/', '233', $number);
    }

    /**
     * Util public static function verifyPhoneNumber.
     * It verifies if a number is correct
     * @param string $number
     * @return boolean
     */
    public static function verifyPhoneNumber($number) {
        return preg_match("/^[0][0-9]{9}$/", $number) ? true : false;
    }

    public static function verify233Format($phoneNumber)
    {
        $phoneNumber = (int)$phoneNumber;
        return is_int($phoneNumber) && str_starts_with($phoneNumber, '233') && strlen($phoneNumber) == 12;
    }

    public static function verifyGHFormat($phoneNumber)
    {
//        return strlen($phoneNumber) == 10;
        return preg_match("/^[0][0-9]{9}$/", $phoneNumber) && str_starts_with($phoneNumber, '0') && strlen($phoneNumber) == 10;
    }

    /**
     * Util public static function verifyNumberLength.
     * It verifies if a number is exactly a particular length
     * @param string $number Number String to verify
     * @param int $length Length to use for validation
     * @return boolean
     */
    public static function verifyNumberLength($number, $length = 10) {
        return preg_match("/^[0-9]{".$length."}$/", $number) ? true : false;
    }

    /**
     * Util public static function verifyWholeNumber.
     * It verifies if a number is a positive integer
     * @param string $number
     * @return boolean
     */
    public static function verifyWholeNumber($number) {
        return preg_match("/^[1-9][0-9]*$/", $number) ? true : false;
    }

    /**
     * Util public static function verifyAmount.
     * It verifies if amount if a correct value
     * @param string $amount
     * @return boolean
     */
    public static function verifyAmount($amount) {
        return preg_match("/^[0-9]+(?:\.[0-9]{1,2})?$/", $amount) ? true : false;
    }

    /**
     * Util public static function verifyNumber.
     * Verifies if number is a single digit number
     * @param string $number
     * @return boolean
     */
    public static function verifyNumber($number) {
        return preg_match("/^[0-9]*$/", $number) ? true : false;
    }


    public static function checkNetworkName(array $response)
    {
        if (isset($response['status']) && $response['status'] == 'OK') {
            if ($response['network'] == 'Vodafone') {
                $response['network'] = 'VOD';
            } else if ($response['network'] == 'AirtelTigo') {
                $response['network'] = 'AIR';
            }  else if ($response['network'] == 'MTN') {
                $response['network'] = 'MTN';
            } else {
                $response['network'] = 'UNKNOWN';
            }

            return $response;
        }
        return $response;
    }
}