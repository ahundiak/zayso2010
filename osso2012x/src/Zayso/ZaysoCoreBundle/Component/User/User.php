<?php
/* ===========================================================
 * Basic user
 */
namespace Zayso\ZaysoCoreBundle\Component\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Role\Role;

class User implements UserInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function getData($name,$default = null)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return $default;
    }
    public function getUsername() { return $this->getData('userName'); }

    public function getPassword() { return $this->getData('userPass'); }

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
    public function getPersonId()        { return $this->getData('personId'); }
    public function getAccountId()       { return $this->getData('accountId'); }
    public function getAccountPersonId() { return $this->getData('accountPersonId'); }
    
    public function setFirstName($value) { $this->data['personFirstName'] = $value; }
    public function setLastName ($value) { $this->data['personLastName' ] = $value; }
    public function setNickName ($value) { $this->data['personNickName' ] = $value; }
    
    public function getName()
    {
        $fname = $this->getData('personFirstName');
        $nname = $this->getData('personNickName');
        $lname = $this->getData('personLastName');

        if ($nname) $fname = $nname;

        return $fname . ' ' . $lname;
    }
    public function getAYSOCertsDescription()
    {
        $aysoid = substr($this->getData('aysoid'),4);
        if (!$aysoid) return 'AYSOID Not Found';

        $region = substr($this->getData('personOrgKey'),4);

        $memYear = 'MY' . $this->getData('mem_year');
        if ($memYear == 'MY')
        {
            return $region  . ', ' . $aysoid . ', ' . 'AYSO Information Not Yet Verified';
        }
        $refBadge = 'Referee Badge: ' . $this->getData('ref_badge');

        $safeHaven = $this->getData('safe_haven');
        if (($safeHaven == 'Coach') || ($safeHaven == 'Referee') || ($safeHaven == 'AYSO')) $safeHaven = 'Yes';
        else                                                                                $safeHaven = 'No';
        $safeHaven = 'Safe Haven: ' . $safeHaven;

        return $region  . ', ' . $aysoid . ', ' . $memYear . ', ' . $refBadge . ', ' . $safeHaven;
    }

    function getSalt()          { return null; }
    function eraseCredentials() { return; }
    
    // This does get called
    function equals(UserInterface $user) 
    { 
        if (!$user instanceof User) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUserName() !== $user->getUsername()) {
            return false;
        }
        return false;     
    }
}
?>
