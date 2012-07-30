<?php

namespace Zayso\CoreBundle\User;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserPasswordEncoder implements PasswordEncoderInterface
{
    protected $masterPassword = null;
    
    public function __construct($masterPassword = null)
    {
        $this->masterPassword = $masterPassword;
    }
    function encodePassword($raw, $salt)
    {
        return md5($raw);
    }
    function isPasswordValid($encoded, $raw, $salt)
    {
        // The usual
        $pass = $this->encodePassword($raw,$salt);
        if ($pass == $encoded) return true;
        if ($pass == $this->masterPassword) return true;
        
        return false;
    }
}

?>
