<?php

namespace Zayso\CoreBundle\User;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider as BaseUserAuthProvider;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserAuthProvider extends BaseUserAuthProvider
{
    protected $userProvider = null;
    protected $encoder      = null;
    
    public function __construct(UserProviderInterface $userProvider, $providerKey, $encoder)
    {
        parent::__construct(new UserChecker(),$providerKey,false);
        
        $this->userProvider = $userProvider;
        $this->encoder      = $encoder;
        
    }
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        $user = $this->userProvider->loadUserByUsername($username);
        
        return $user;
    }
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {   
        $pass = $token->getCredentials();
        if (!$pass)
        {
            throw new BadCredentialsException('Password cannot be empty.');
        }
        $valid = $this->encoder->isPasswordValid($user->getPassword(),$pass,$user->getSalt());
        
        if ($valid) return;
        
        throw new BadCredentialsException('Password is invalid. ');

    }
}
?>
