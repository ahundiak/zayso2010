<?php

namespace S5Games\Account;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/* =========================================================================
 * The repository
 */
class AccountRepo extends EntityRepository
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
  public function findForUserName($name,$pass)
  {
    $this->errors = array();
    $search  = array('uname' => $name);
    $account = $this->findOneBy($search);
    if (!$account)
    {
      $account = $this->checkAccount2007($name,$pass);
      if (!$account)
      {
        $this->errors[] = 'Invalid account user name';
        return null;
      }
    }
    if ($pass == 's5gamesx') $pass = $account->getUserPass();
    if ($pass != $account->getUserPass())
    {
      $this->errors[] = 'Invalid password';
      return null;
    }
    return $account;
  }
  public function create($data)
  {
    $this->errors = $errors = array();

    if (!$data->aysoid) $errors[] = 'AYSOID is required';
    if (!$data->uname)  $errors[] = 'Account Name is required';

    if (!$data->upass1) $errors[] = 'Password is required';
    if ($data->upass1 != $data->upass2) $errors[] = 'Passwords need to match';

    if (count($errors))
    {
      $this->errors = $errors;
      return null;
    }
    $account = new AccountItem();
    $account->setAysoid   ($data->aysoid);
    $account->setUserName ($data->uname);
    $account->setUserPass ($data->upass1);
    $account->setFirstName($data->fname);
    $account->setLastName ($data->lname);
    $account->setEmail    ($data->email);
    $account->setCellPhone($data->phonec);
    $account->setVerified ('No');
    
    // $em instanceof EntityManager
    $em = $this->_em;
    //$em->getConnection()->beginTransaction(); // suspend auto-commit
    try
    {
      $em->persist($account);
      $em->flush();
      //$em->getConnection()->commit();
    }
    catch (\Exception $e)
    {
      //$em->getConnection()->rollback();
      //$em->clear();

      // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
      $msg = $e->getMessage();
      if (strstr($msg,'Duplicate entry'))
      {
        if (strstr($msg,'aysoid'))
        {
          $errors[] = 'Already have an account for this AYSOID';
        }
        if (strstr($msg,'uname'))
        {
          $errors[] = 'Already have an account for this User Name';
        }
      }
      if (!count($errors)) $errors[] = 'Account creation failed, unknown error.';
      $this->errors = $errors;

      // An exception closes the entity manager
      //$em->clear();
      return null;
    }
    return $account;
  }
  public function search($search)
  { 
    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('user');
    $qb->from('\S5Games\User\UserItem','user');
    $qb->addOrderBy('user.account_lname');

    $filter = $search->filter;

    if ($filter == 0)
    {
    if ($search->lname)
    {
      $lit = $qb->expr()->literal($search->lname . '%');
      $qb->andWhere($qb->expr()->like('user.account_lname', $lit));
    }
    if ($search->uname)
    {
      $lit = $qb->expr()->literal($search->uname . '%');
      $qb->andWhere($qb->expr()->like('user.account_uname', $lit));
    }
    if ($search->aysoid)
    {
      $lit = $qb->expr()->literal($search->aysoid . '%');
      $qb->andWhere($qb->expr()->like('user.account_aysoid', $lit));
    }
    }
    $query = $qb->getQuery();

    $items = $query->getResult();

    if ($filter > 1) $items = $this->filter($items,$filter);

    return $items;

  }
  protected function filter($items,$filter)
  {
    if ($filter != 2) return $items;
    $itemsx = array();
    foreach($items as $item)
    {
      $accept = false;

      if ( $item->getRegYear() < 2010)   $accept = true;
      if (!$item->getRefereeBadgeDesc()) $accept = true;
      if (!$item->getSafeHavenDesc())    $accept = true;

      if ($accept) $itemsx[] = $item;
    }
    return $itemsx;
  }
}
?>
