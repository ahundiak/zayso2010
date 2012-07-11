<?php
/* ===========================================================
 * Basic user
 */
namespace Zayso\NatGamesBundle\Component\User;

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
                return array(new Role('ROLE_SUPER_ADMIN'), new Role('ROLE_SCORER'), new Role('ROLE_SCORERX'));
                
            case 'AYSOV90001476': // David Holt
            case 'AYSOV98037803': // Jack Graham
            case 'AYSOV53319472': // Diane S
            case 'AYSOV56063435': // Bob Deene
            case 'AYSOV96286066': // Tom B
            case 'AYSOV91000961': // Mike F
            case 'AYSOV52552170': // Bill Mize
                return array(new Role('ROLE_ADMIN'));
                
//            case 'AYSOV53218432': // Jeff Ward
//                return array(new Role('ROLE_SCORER'));
                
            case 'AYSOV59172591': // Cassie Hundiak
                return array(new Role('ROLE_SCORER'), new Role('ROLE_SCORERX'));
        }
        return array(new Role('ROLE_USER'));
    }
}
?>
