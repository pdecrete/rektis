<?php

namespace admapp\Util;

//use Yii;

/**
 * Description of Common
 *
 * @author spapad
 */
class Core
{

    /**
     * Generate pseudo random hash 
     * 
     * @param int $bits
     * @return string A string with length 2*$bits
     */
    public static function generateToken($bits = 10)
    {
        return bin2hex(openssl_random_pseudo_bytes($bits));
    }

}
