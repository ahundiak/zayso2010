<?php

namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

class AccountCreateData
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    function __get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return '';
    }
}
/* =========================================================================
 * The repository
 */
class AccountRepository extends EntityRepository
{
  protected $errors = array();

  public function getErrors() { return $this->errors; }

  protected function checkAccount2007($name,$pass)
  {
    $repo = $this->_em->getRepository('S5Games\Account\Account2007Item');
    $account2007 = $repo->findOneBy(array('_uname' => $name));
    if (!$account2007) return null;

    if (!$account2007->aysoid) return null;

    $account = new AccountItem();
    $account->setAysoid   ($account2007->aysoid);
    $account->setUserName ($name);
    $account->setUserPass ($pass);
    $account->setFirstName($account2007->fname);
    $account->setLastName ($account2007->lname);
    $account->setVerified ('No');

    $this->_em->persist($account);
    $this->_em->flush();
    return $account;

    die($account2007->fname);
  }
  /* ========================================================
   * Used by sign in
   */
  public function findForUserName($name,$pass)
  {
    $this->errors = array();
    $search  = array('_uname' => $name);
    $account = $this->findOneBy($search);
    if (!$account)
    {
      // $account = $this->checkAccount2007($name,$pass);
      if (!$account)
      {
        $this->errors[] = 'Invalid account user name';
        return null;
      }
    }
    if ($pass == 'ngamesx') $pass = $account->upass;
    if (strlen($pass) != 32) $pass = md5($pass);
    if ($pass != $account->upass)
    {
      $this->errors[] = 'Invalid password';
      return null;
    }
    return $account;
  }
    // Symfony2 account create
    public function create($data)
    {
        $em = $this->_em;
        $errors = array();
        if (is_array($data)) $data = new AccountCreateData($data);

        // Need a person
        $person = $em->getRepository('ZaysoBundle:Person')->create($data);
        if (is_array($person))
        {
            $errors = $person;
            $person = null;
        }

        // Some error checking
        if (!$data->uname)  $errors[] = 'Account Name is required';
        if (!$data->upass1) $errors[] = 'Password is required';
        if ( $data->upass1 != $data->upass2) $errors[] = 'Passwords need to match';

        if (count($errors)) return $errors;

        $account = new Account();
        $account->setUname($data->uname);
        $account->setUpass(md5($data->upass1));
        $account->setStatus('Active');

        $accountPerson = new AccountPerson();
        $accountPerson->setAccount($account);
        $accountPerson->setPerson ($person);
        
        $accountPerson->setRelId(1);
        $accountPerson->setVerified('No');
        $accountPerson->setStatus('Active');

        // $em instanceof EntityManager
        $em = $this->_em;
        try
        {
            $em->persist($account);
            $em->flush();
        }
        catch (\Exception $e)
        {
            // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
            // 1062 Duplicate entry 'User 2' for key 2Array
            $msg = $e->getMessage(); // echo $msg;
            $errors[] = 'Already have an account for this Account Name';

            if (strstr($msg,'Duplicate entry'))
            {
                if (strstr($msg,'aysoid'))
                {
                    $errors[] = 'Already have an account for this AYSOID';
                }
                if (strstr($msg,'uname'))
                {
                    $errors[] = 'Already have an account for this Account Name';
                }
            }
            if (!count($errors)) $errors[] = 'Account creation failed, unknown error.';
            return $errors;
        }
        return $account;
    }
  public function createx($data)
  {
    $this->errors = $errors = array();

    if (!$data->aysoid) $errors[] = 'AYSOID is required';
    if (!$data->uname)  $errors[] = 'Account Name is required';
    if (!$data->fname)  $errors[] = 'AYSO First Name is required';
    if (!$data->lname)  $errors[] = 'AYSO Last Name is required';
    if (!$data->email)  $errors[] = 'Email is required';

    if (!$data->upass1) $errors[] = 'Password is required';
    if ($data->upass1 != $data->upass2) $errors[] = 'Passwords need to match';

    if (count($errors))
    {
      $this->errors = $errors;
      return null;
    }

    // Make sure have a person
    $personRepo = $this->services->personRepo;
    $person = $personRepo->create($data);
    if (!$person)
    {
      $this->errors[] = 'Problem creating person record';
      return null;
    }
    $account = new AccountItem();
    $account->uname = $data->uname;
    $account->upass = md5($data->upass1);

    $accountPerson = new AccountPersonItem();
    $accountPerson->account  = $account;
    $accountPerson->person   = $person;
    $accountPerson->relId    = 1;
    $accountPerson->verified = 'No';
  
    // $em instanceof EntityManager
    $em = $this->_em;
    try
    {
      $em->persist($account);
      $em->persist($accountPerson);
      $em->flush();
    }
    catch (\Exception $e)
    {
      // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
      $msg = $e->getMessage(); echo $msg . "\n";
      if (strstr($msg,'Duplicate entry'))
      {
        if (strstr($msg,'aysoid'))
        {
          $errors[] = 'Already have an account for this AYSOID';
        }
        if (strstr($msg,'uname'))
        {
          $errors[] = 'Already have an account for this Account Name';
        }
      }
      if (!count($errors)) $errors[] = 'Account creation failed, ' . $msg;
      $this->errors = $errors;

      return null;
    }
    // Now store some project-person info
    $projectRepo = $this->services->projectRepo;
    $projectPerson = $projectRepo->loadProjectPerson($data->projectId,$person);

    $info = new \NatGames\DataItem();
    $info->refBadge = $data->refBadge;
    $info->region   = $data->region;
    
    $projectPerson->accountCreate = $info;
    
    $em->persist($projectPerson);
    $em->flush();
    
    return $account;
  }
  public function search($search)
  {
    die('account-search');
    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('user');
    $qb->from('\S5Games\User\UserItem','user');
    $qb->addOrderBy('user.account_lname');

    $query = $qb->getQuery();

    $items = $query->getResult();
    return $items;

  }
}
?>
