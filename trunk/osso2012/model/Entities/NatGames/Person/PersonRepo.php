<?php

namespace NatGames\Person;

/* =========================================================================
 * The person repository
 */
class PersonRepo extends \NatGames\EntityRepo
{
  protected $errors = array();

  public function getErrors() { return $this->errors; }

  public function create($data)
  {
    // Some basic error checking
    $this->errors = $errors = array();

    if (!$data->aysoid) $errors[] = 'AYSOID is required';
    if (!$data->fname)  $errors[] = 'AYSO First Name is required';
    if (!$data->lname)  $errors[] = 'AYSO Last Name is required';
    if (!$data->email)  $errors[] = 'Email is required';

    if (count($errors))
    {
      $this->errors = $errors;
      return null;
    }
    // See if already exists
    $person = $this->findForAysoid($data->aysoid);
    if ($person) return $person;

    $person = new \NatGames\Person\PersonItem();

    $person->fname    = $data->fname;
    $person->lname    = $data->lname;
    $person->nname    = $data->nname;
    $person->email    = $data->email;
    $person->phonec   = $data->phonec;

    $personReg = new \NatGames\Person\PersonRegItem();
    $personReg->regType = 'AYSOV';
    $personReg->regKey  = 'AYSOV-' . $data->aysoid;
    $personReg->person  = $person;
    
    // $em instanceof EntityManager
    $em = $this->_em;
    try
    {
      $em->persist($person);
      $em->persist($personReg);
      $em->flush();
    }
    catch (\Exception $e)
    {
      // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
      $msg = $e->getMessage();
      if (strstr($msg,'Duplicate entry'))
      {
      
      }
      if (!count($errors)) $errors[] = 'Person creation failed, unknown error.';
      $this->errors = $errors;

      return null;
    }
    return $person;
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
  public function findForAysoid($aysoid)
  {
    if (strlen($aysoid) == 8) $aysoid = 'AYSOV-' . $aysoid;

    $em = $this->_em;

    $search = array('_regKey' => $aysoid);

    $personReg = $em->getRepository('NatGames\Person\PersonRegItem')->findOneBy($search);

    if (!$personReg) return null;

    $person = $personReg->person;
    if (!$person) return null;

    $person->addReg($personReg);
    
    return $person;

  }
}
?>
