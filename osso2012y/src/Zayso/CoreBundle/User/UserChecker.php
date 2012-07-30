<?php
namespace Zayso\CoreBundle\User;

use Symfony\Component\Security\Core\User\UserChecker as BaseUserChecker;

class UserChecker extends BaseUserChecker
{
    // Only does somethinf for advanced users
    // public function checkPreAuth(UserInterface $user)
    // public function checkPostAuth(UserInterface $user)
}

?>
