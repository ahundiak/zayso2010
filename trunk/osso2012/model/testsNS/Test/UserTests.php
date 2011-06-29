<?php

namespace Test;

use \Cerad\Debug;

class UserTests extends BaseTests
{
  function testUser()
  {
    $user = new \NatGames\UserItem($this->services);
  }
  function sestDQL()
  {
    $em = $this->em;
    $dql = <<<EOT
SELECT account, member
FROM \NatGames\Account\AccountItem account
LEFT JOIN  account._members member

WHERE account._id = 1
EOT;
    $query = $em->createQuery($dql);

    $accounts = $query->getResult();

    $this->assertEquals(1,count($accounts));
  }
  function sestCleanup()
  {
    $em = $this->em;
    $em->clear();

    $query = $em->createQuery('DELETE OSSO2012\AccountItem;');
    $query->getResult();

    $query = $em->createQuery('DELETE OSSO2012\PersonItem;');
    $query->getResult();
    
    $query = $em->createQuery('DELETE OSSO2012\AccountPersonItem;');
    $query->getResult();

  }
  /**
   * expectedException Exception
   */
  function sestAccountCreation()
  {
    $em = $this->em;
    $em->clear();

    // Account
    $account = new \OSSO2012\AccountItem();
    $account->setName('ahundiak');
    $account->setPass('password');
    $em->persist($account);

    // Person
    $person = new \OSSO2012\PersonItem();
    $person->setFirstName('Art');
    $person->setLastName('Hundiak');
    $person->setGuid('99437977');
    $em->persist($person);

    // Account Person
    $ap = new \OSSO2012\AccountPersonItem();
    $ap->setAccount($account);
    $ap->setPerson ($person);
    $em->persist($ap);

    try
    {
      $em->flush();
    }
    catch (\PDOException $e)
    {
      $class = get_class($e);
      // echo $e->getCode(); // 23000
      echo $e->getMessage();
      // print_r($e);
      // echo "Exception Caught"; die('Caught');
      return;
    }
  }
  function sestUserView()
  {
    $em = $this->em;

    $search = array('account_uname' => 'ahundiak');

    $user = $em->getRepository('OSSO2012\User\UserItem')->findOneBy($search);

    $this->assertEquals('ahundiak',$user->getAccountName());
    $this->assertEquals(2010,      $user->getRegYear());

    $this->assertEquals('Safe Haven Coach',  $user->getSafeHavenDesc());
    $this->assertEquals('Intermediate Coach',$user->getCoachBadgeDesc());
    $this->assertEquals('Advanced Referee',  $user->getRefereeBadgeDesc());

  }
}
?>
