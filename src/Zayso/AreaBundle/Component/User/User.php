<?php
/* ===========================================================
 * Basic user
 */
namespace Zayso\AreaBundle\Component\User;

use Zayso\CoreBundle\Component\User\User as BaseUser;

use Symfony\Component\Security\Core\Role\Role;

class User extends BaseUser
{
    public function getRoles() 
    {
        $id = $this->getPersonId();
        switch($id)
        {
            case 1: // Art H
                return array(new Role('ROLE_SUPER_ADMIN'));
                
            case  44: // Debbie Farmer
            case 355: // James Farmer
            case 337: // Jim Meehan
                
            case 406: // Vernon Paulett
            case 453: // Les Daniel
            case 485: // Ray Cassell
                
            case 263: // Rod Etzel
            case 275: // Chris Steely
            case 226: // Aaron Luchini
                
                return array(new Role('ROLE_ADMIN'));
        }
        return array(new Role('ROLE_USER'));
    }
}
?>
