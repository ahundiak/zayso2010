<?php
/* =======================================================
 * Not using this
 */
namespace Zayso\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_person")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $username;
    protected $password;
    protected $roles = array();
    
    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    function getRoles() { return $this->roles; }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return string The password
     */
    function getPassword() { return $this->password; }

    /**
     * Returns the salt.
     *
     * @return string The salt
     */
    function getSalt() { return null; }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    function getUsername() { return $this->username; }

    /**
     * Removes sensitive data from the user.
     *
     * @return void
     */
    function eraseCredentials() { return; }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @param UserInterface $user
     * @return Boolean
     */
    function equals(UserInterface $user) { return true; }
    
}

?>
