<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zayso\CoreBundle\Component\User;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Wrapper around a Doctrine EntityManager.
 *
 * Provides easy to use provisioning for Doctrine entity users.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class UserProviderOld implements UserProviderInterface
{
    private $em;
    private $projectId;
    private $userClass;

    // Started as a clone of EntityUserManager
    public function __construct(EntityManager $em, $userClass, $projectId = 0)
    {
        $this->em = $em;
        $this->userClass = $userClass;
        $this->projectId = (int)$projectId;
        return;
    }

    public function loadUserByUsernameOld($username)
    {
        // Called during the sign in process
        // Called again with setUser
        // die('loadUserByUsername');
        
        $sql = <<<EOT
SELECT
  account.id        AS accountId,
  account.user_name AS userName,
  account.user_pass AS userPass,
  account.status    AS accountStatus,

  account_person.id AS accountPersonId,
  account_person.person_id AS personId,

  person.first_name AS personFirstName,
  person.last_name  AS personLastName,
  person.nick_name  AS personNickName,
  person.org_key    AS personOrgKey,
  person.datax      AS person_datax,

  person_registered.reg_key AS aysoid,
  person_registered.datax   AS person_registered_datax

FROM account

LEFT JOIN account_person    ON account_person.account_id   = account.id AND account_person.account_relation = 'Primary'
LEFT JOIN person            ON person.id                   = account_person.person_id
LEFT JOIN person_registered ON person_registered.person_id = person.id AND person_registered.reg_type = 'AYSOV'

WHERE account.user_name = :userName
;
EOT;
        $db = $this->em->getConnection();
        $stmt = $db->prepare($sql);
        $params = array('userName' => $username);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Make sure got one
        if (!$row)
        {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        // Bit of processing
        if (isset($row['person_datax']))
        {
            $datax = unserialize($row['person_datax']);
            unset($row['person_datax']);
            if (is_array($datax)) $row = array_merge($row,$datax);
        }
        if (isset($row['person_registered_datax']))
        {
            $datax = unserialize($row['person_registered_datax']);
            unset($row['person_registered_datax']);
            if (is_array($datax)) $row = array_merge($row,$datax);
        }
        
        // Now do a query for roles
        // 
        
        // Build the user
        $user = new $this->userClass($row);

        //print_r($row);
        //die('load user ' . $username);

        return $user;
    }
    /* =========================================================
     * This is fne for now but this will alwys load the primary accunt holder
     * and not any secondary ones that were connected
     * But only support promary for not anyways
     */
    public function loadUserByOpenidIdentifier($identifier)
    {
        $userName = $this->getUsernameForOpenidIdentifier($identifier);
        return $this->loadUserByUsername($userName);
    }
    public function getUsernameForOpenidIdentifierOld($identifier)
    {
        $sql = <<<EOT
SELECT
  account.user_name AS userName

FROM account_openid

LEFT JOIN account_person ON account_person.id = account_openid.account_person_id
LEFT JOIN account        ON account.id        = account_person.account_id

WHERE account_openid.identifier = :identifier
;
EOT;
        $db = $this->em->getConnection();
        $stmt = $db->prepare($sql);
        $params = array('identifier' => $identifier);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Make sure got one
        if (!$row)
        {
            throw new UsernameNotFoundException(sprintf('User Openid Identifier not found.'));
        }
        return $row['userName'];
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // Appears to be called when page refreshes
        //die('refreshUser');
        return $user;

        if (!$user instanceof $this->class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return true;

        return $class === $this->class;
    }
}
