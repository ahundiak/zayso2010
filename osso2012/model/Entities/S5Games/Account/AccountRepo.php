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

  public function findForUserName($name,$pass)
  {
    $this->errors = array();
    $search  = array('uname' => $name);
    $account = $this->findOneBy($search);
    if (!$account)
    {
      $this->errors[] = 'Invalid account user name';
      return null;
    }
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

}
?>
