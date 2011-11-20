<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zayso\ZaysoBundle\Component\Security\User;

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
class MyUserProvider implements UserProviderInterface
{
    private $class;
    private $repository;
    private $property;

    private $em;

    // Started as a clone of EntityUserManager
    public function __construct(EntityManager $em, $class = null, $property = null)
    {
        $this->em = $em;
        return;

        die('MyUserManager ' . $class);
        $this->class = $class;

        if (false !== strpos($this->class, ':')) {
            $this->class = $em->getClassMetadata($class)->name;
        }

        $this->repository = $em->getRepository($class);
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
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
            $row = array_merge($row,$datax);
        }
        if (isset($row['person_registered_datax']))
        {
            $datax = unserialize($row['person_registered_datax']);
            unset($row['person_registered_datax']);
            $row = array_merge($row,$datax);
        }
        // Build the user
        $user = new MyUser($row);

        //print_r($row);
        //die('load user ' . $username);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // Appears to be called when page refreshes
        // die('refreshUser');
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
