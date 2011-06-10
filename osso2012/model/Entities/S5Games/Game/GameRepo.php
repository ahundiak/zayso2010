<?php

namespace S5Games\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/* =========================================================================
 * The repository
 */
class GameRepo extends EntityRepository
{
  protected $errors = array();

  public function getErrors() { return $this->errors; }

  protected function filterGame($game,$coaches,$referees)
  {
    // Check for any filters
    if ((count($coaches) == 0) && (count($referees) == 0)) return true;

    foreach($coaches as $coach)
    {
      if (!(stristr($game->getHomeTeam(),$coach) === FALSE)) return true;
      if (!(stristr($game->getAwayTeam(),$coach) === FALSE)) return true;
    }
    $persons = $game->getPersons();
    foreach($referees as $referee)
    {
      foreach($persons as $person)
      {
        if (!(stristr($person->getLastName(), $referee) === FALSE)) return true;
        if (!(stristr($person->getFirstName(),$referee) === FALSE)) return true;
      }
    }
  }
  protected function explode($search)
  {
    $searches = explode(',',$search);
    $results = array();
    foreach($searches as $search)
    {
      $search = trim($search);
      if ($search) $results[] = $search;
    }
    return $results;
  }
  protected function filterGames($games,$coaches,$referees)
  {
    $coaches  = $this->explode($coaches);
    $referees = $this->explode($referees);
    
    if (!count($coaches) && !count($referees)) return $games;
    
    $gamesx = array();
    foreach($games as $game)
    {
      if ($this->filterGame($game,$coaches,$referees)) $gamesx[] = $game;
    }
    return $gamesx;
  }
  public function search($search)
  {
    $dates = array();
    if ($search->showFri) $dates[] = 'FRI';
    if ($search->showSat) $dates[] = 'SAT';
    if ($search->showSun) $dates[] = 'SUN';
    if (!count($dates))   $dates[] = 'NONE';


    $divs = array();
    if ($search->showU10 && $search->showCoed) $divs[] = 'U10B';
    if ($search->showU10 && $search->showGirl) $divs[] = 'U10G';
    if ($search->showU12 && $search->showCoed) $divs[] = 'U12B';
    if ($search->showU12 && $search->showGirl) $divs[] = 'U12G';
    if ($search->showU14 && $search->showCoed) $divs[] = 'U14B';
    if ($search->showU14 && $search->showGirl) $divs[] = 'U14G';
    if ($search->showU16 && $search->showCoed) $divs[] = 'U16B';
    if ($search->showU16 && $search->showGirl) $divs[] = 'U16G';
    if ($search->showU19 && $search->showCoed) $divs[] = 'U19B';
    if ($search->showU19 && $search->showGirl) $divs[] = 'U19G';

    $coaches  = explode(',',trim($search->coach));
    $referees = explode(',',trim($search->referee));

    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('game');
    $qb->addSelect('person');
    $qb->from('\S5Games\Game\GameItem','game');
    $qb->leftJoin('game.persons','person');
    $qb->andWhere($qb->expr()->in('game.date',$dates));
    $qb->andWhere($qb->expr()->in('game.div', $divs));

    // Need to fool with referees once imnplemented
    // This almost worked but not quite, does not pick up all slots
    $orPeople = $qb->expr()->orx();
    foreach($coaches as $person)
    {
      $person = trim($person);
      if ($person)
      {
        $personx = $qb->expr()->literal('%' . $person . '%');

        $orPeople->add($qb->expr()->like('game.homeTeam', $personx));
        $orPeople->add($qb->expr()->like('game.awayTeam', $personx));

        // Might be too clever
        $orPeople->add($qb->expr()->like('game.field',   $personx));
        $orPeople->add($qb->expr()->like('game.div',     $personx));
        $orPeople->add($qb->expr()->like('game.bracket', $personx));
      }
    }
    foreach($referees as $person)
    {
      $person = trim($person);
      if ($person)
      {
        $personx = $qb->expr()->literal('%' . $person . '%');

        $orPeople->add($qb->expr()->like('person.lname', $personx));
        $orPeople->add($qb->expr()->like('person.fname', $personx));
      }
    }
    // $qb->andWhere($orPeople);

    switch($search->sort)
    {
      case 1:
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('game.div');
        $qb->addOrderBy('game.field');
        break;

      case 2:
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.field');
        $qb->addOrderBy('game.time');
        break;

      case 3:
        $qb->addOrderBy('game.div');
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('game.field');
        break;

      case 4:
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.div');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('game.field');
        break;

      case 5: 
        $qb->addOrderBy('game.id');

    }
    //die($qb->getDQL());
    $query = $qb->getQuery();
    
    $games = $query->getResult();
    
    $games = $this->filterGames($games,$search->coach,$search->referee);

    return $games;
  }
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
