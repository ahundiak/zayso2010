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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateAccountOpenid();
        
        $this->updateAccountPerson();
        
        $this->updatePersonPerson();
    }
}
?>
