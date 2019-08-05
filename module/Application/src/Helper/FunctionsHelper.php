<?php
namespace Application\Helper;

/**
 * Helpful functions.
 *
 * Class FunctionsHelper
 * @package Application\Helper
 */
class FunctionsHelper {

    /**
     * Generage random string.
     *
     * @param int $length
     * @return string
     */
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }
}