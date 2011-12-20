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
        $aysoid = $this->getData('aysoid');
        switch($aysoid)
        {
            case 'AYSOV99437977': // Art H
                return array(new Role('ROLE_SUPER_ADMIN'));
                
            case 'AYSOV90001476': // David Holt
            case 'AYSOV98037803': // Jack Graham
            case 'AYSOV53319472': // Diane S
            case 'AYSOV56063435': // Bob Deene
            case 'AYSOV96286066': // Tom B
            case 'AYSOV91000961': // Mike F
                return array(new Role('ROLE_ADMIN'));
        }
        return array(new Role('ROLE_USER'));
    }
}
?>
