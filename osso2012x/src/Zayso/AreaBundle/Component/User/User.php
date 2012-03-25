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
                
            case 406: // Vernon Paulett
                return array(new Role('ROLE_ADMIN'));
        }
        return array(new Role('ROLE_USER'));
    }
}
?>
