<?php

namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\CoreBundle\Entity\PersonPerson;

class UpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:update')
            ->setDescription('Schema')
        ;
    }
    protected function updateAccountOpenid()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('openid, accountPerson, account');
        
        $qb->from('ZaysoCoreBundle:AccountOpenid', 'openid');
        $qb->leftJoin('openid.accountPerson',      'accountPerson');
        $qb->leftJoin('accountPerson.account',     'account');

        $openids = $qb->getQuery()->getResult();
        
        foreach($openids as $openid)
        {
            $account = $openid->getAccountPerson()->getAccount();
            $openid->setAccount($account);
        }
        $em->flush();
    }
    protected function updateAccountPerson()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('accountPerson, person, account');
        
        $qb->from('ZaysoCoreBundle:AccountPerson', 'accountPerson');
        $qb->leftJoin('accountPerson.person',      'person');
        $qb->leftJoin('accountPerson.account',     'account');

        $accountPersons = $qb->getQuery()->getResult();
        
        foreach($accountPersons as $accountPerson)
        {
            $account = $accountPerson->getAccount();
            $person  = $accountPerson->getPerson();
            
            if ($accountPerson->isPrimary())
            {
                $account->setPerson($person);
            }
        }
        $em->flush();
    }
    protected function updatePersonPerson()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('accountPerson, person, account');
        
        $qb->from('ZaysoCoreBundle:AccountPerson', 'accountPerson');
        $qb->leftJoin('accountPerson.person',      'person');
        $qb->leftJoin('accountPerson.account',     'account');
        $qb->orderBy ('accountPerson.id');
        
        $accountPersons = $qb->getQuery()->getResult();
        
        $accounts = array();
        
        // One loop to build up primary account person
        foreach($accountPersons as $accountPerson)
        {
            $account = $accountPerson->getAccount();
            $person  = $accountPerson->getPerson();
            
            if ($accountPerson->isPrimary())
            {
                $accounts[$account->getId()] = $person;
            }
        }
        $pp = array();
        
        // One loop to make Person Person
        foreach($accountPersons as $accountPerson)
        {
            $account = $accountPerson->getAccount();
            $person2 = $accountPerson->getPerson();
            
            $person1 = $accounts[$account->getId()];
            $relation = $accountPerson->getAccountRelation();
            
            if (!isset($pp[$person1->getId()][$person2->getId()][$relation]))
            {
                $personPerson = new PersonPerson();
                $personPerson->setPerson1 ($person1);
                $personPerson->setPerson2 ($person2);
                $personPerson->setRelation($relation);
            
                $em->persist($personPerson);
                $pp[$person1->getId()][$person2->getId()][$relation] = true;
            }
        }
        $em->flush();
    }
    protected function updatePersonRegistered()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person, personReg');
        
        $qb->from('ZaysoCoreBundle:Person', 'person');
        $qb->leftJoin('person.registeredPersons','personReg');
        
        $persons = $qb->getQuery()->getResult();
        
        foreach($persons as $person)
        {
            $personRegs = $person->getRegisteredPersons();
            if (count($personRegs) != 1)
            {
                // Verify 1 to 1 at this point
                echo sprintf("Person %d %d\n",$person->getId(),count($personRegs()));
            }
            $org = $person->getOrg();
            if (!$org)
            {
                echo sprintf("Person %d NO ORG\n",$person->getId());               
            }
            foreach($personRegs as $personReg)
            {
                $personReg->setOrg($org);
            }
        }
        $em->flush();
    }
    protected function updatePerson()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        
        $qb->from('ZaysoCoreBundle:Person', 'person');
        
        $persons = $qb->getQuery()->getResult();
        
        foreach($persons as $person)
        {
            $dob = $person->get('dob');
            if ($dob) $person->setDob($dob);
            $person->set('dob',null);
            
            $gender = $person->get('gender');
            if ($gender) $person->setGender($gender);
            $person->set('gender',null);
           
            $person->clearData();            
        }
        $em->flush();
    }
    protected function updateOrg()
    {
        $em = $this->getContainer()->get('zayso_core.osso2012.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('org');
        
        $qb->from('ZaysoCoreBundle:Org', 'org');
        
        $orgs = $qb->getQuery()->getResult();
        
        echo sprintf("Org Count %d %s\n",count($orgs),$orgs[10]->getDesc1());
        
        $org = $orgs[210];
        
        echo sprintf("ORG %s %s %s %s\n",$org->getId(),$org->getDesc1(),$org->getState(), $org->getStatus());
        
      //$org->setState('ZZ');
        $org->setState('Z1');
        $org->setStatus('Active1');
        $em->flush();
        
    }
     protected function updateAccount()
    {
        $em = $this->getContainer()->get('zayso_core.osso2012.entity_manager');
        
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account','accountOpenid','person');
        
        $qb->from('ZaysoCoreBundle:Account', 'account');
        
        $qb->leftJoin('account.openids', 'accountOpenid');
        $qb->leftJoin('account.person',  'person');
       
        $accounts = $qb->getQuery()->getResult();
        
        $account = $accounts[0];
        $person  = $account->getPerson();
        $openids = $account->getOpenids();
        $openid = $openids[1];
        
        echo sprintf("Account Count %d %s %s %s %s\n",count($accounts),$account->getUsername(),
                $person->getName(),$person->getRegAYSOV()->getAysoid(),$openid->getProvider());
        return;
        
        $org = $orgs[210];
        
        echo sprintf("ORG %s %s %s %s\n",$org->getId(),$org->getDesc1(),$org->getState(), $org->getStatus());
        
      //$org->setState('ZZ');
        $org->setState('Z1');
        $org->setStatus('Active1');
        $em->flush();
        
    }
   protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$this->updateAccountOpenid();
        
        //$this->updateAccountPerson();
        
        //$this->updatePersonPerson();
        
        // $this->updatePerson();
        
        // $this->updatePersonRegistered();
        $this->updateAccount();
    }
}
?>
