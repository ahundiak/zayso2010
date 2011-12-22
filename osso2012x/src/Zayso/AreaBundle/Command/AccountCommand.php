<?php

namespace Zayso\AreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AccountCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:account')
            ->setDescription('Account Tests')
        ;
    }
    protected function getAccountManager() { return $this->getContainer()->get('zayso_area.account.manager'); }

    protected function test1()
    {
        $accountManager = $this->getAccountManager();
        $account = $accountManager->loadAccountForUserName('ahundiak');
        $this->dumpAccount($account);
        $accountManager->getEntityManager()->clear();
        
    }
    protected function test2()
    {
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPerson();

        $accountPerson->setUserName('ahundiak06');
        $accountPerson->setUserPass('zz');

        $accountPerson->setFirstName('Art');
        $accountPerson->setLastName ('Hundiak');
        $accountPerson->setEmail    ('ahundiak@gmail.com');

        $accountPerson->setAysoid('11223304');
        $accountPerson->setRefBadge('Advanced');

        $accountPerson->setRegion('AYSOR9903');

        $accountManager->createAccountFromAccountPerson($accountPerson,70);

        $accountManager->getEntityManager()->clear();

    }
    protected function test3()
    {
        echo "====================\n";
        
        $validator = $this->getContainer()->get('validator');

        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPersonAyso();

        $accountPerson->setFirstName(''); // Confirms empty string is blank
        $accountPerson->setEmail    ('ahundiak@gmailcom');
        $accountPerson->setRegion   ('O894');
        $accountPerson->setAysoid   ('AYSOR9903');

        $errors = $validator->validate($accountPerson, array('create'));
        foreach($errors as $error)
        {
            echo 'Error: ' . $error->getMessage() . "\n";
        }
        echo "---\n";
        
        $accountPerson->setUserName('test03-03');
        $accountPerson->setUserPass('zz');

        $accountPerson->setFirstName('Art03');
        $accountPerson->setLastName ('Hundiak');

        $accountPerson->setEmail    ('ahundiak@gmail.com');
        $accountPerson->setCellPhone('2564575943');
        $accountPerson->setRegion   ('AYSOR0894');

        $accountPerson->setAysoid   ('AYSOV11220301');
        $accountPerson->setRefBadge ('Advanced');

        $accountPerson->setOpenidProvider   ('Google');
        $accountPerson->setOpenidIdentifier ('Google Unique 03');
        $accountPerson->setOpenidDisplayName('Bill Striker');

        $accountPerson->setProjectPersonData(70);

        $errors = $validator->validate($accountPerson, array('create'));
        foreach($errors as $error)
        {
            echo 'Error: ' . $error->getMessage() . "\n";
        }
        $accountPersonReal = $accountPerson->getAccountPerson();

        $account = $accountManager->createAccountFromAccountPersonAyso($accountPersonReal);

        $this->dumpAccount($account);

    }
    protected function dumpAccount($account)
    {
        if (!$account)
        {
            echo "*** NULL Account\n";
            return;
        }
        $accountPerson = $account->getPrimaryAccountPerson();
        if (!$accountPerson)
        {
            echo "*** NULL AccountPerson\n";
            return;
        }
        $person = $accountPerson->getPerson();
        if (!$person) { echo "No person yet\n"; return; }
        
        echo "====================\n";
        echo 'Account ' . $account->getUserName() . ' ' . $account->getStatus() . "\n";
        echo 'AP      ' . $accountPerson->getAccountRelation() . ' ' . $accountPerson->getVerified() . "\n";
        echo 'Person  ' . $accountPerson->getPersonName() . ' ' . $person->getDob() . ' ' . $person->getGender() . "\n";

        echo 'VOL     ' .
            $person->getAysoid()    . ' ' .
            $person->getOrgKey()    . ' ' .
            $person->getMemYear()   . ' ' .
            $person->getRefBadge()  . ' ' .
            $person->getRefDate()   . ' ' .
            $person->getSafeHaven() . ' ' .
            "\n";

        $openids = $accountPerson->getOpenids();
        foreach($openids as $openid)
        {
            echo 'OPENID  ' . $openid->getProvider() . ' ' . $openid->getDisplayName() . "\n";
        }
        $projectPersons = $person->getProjectPersons();
        foreach($projectPersons as $projectPerson)
        {
            echo 'PROJECT ' . $projectPerson->getProject()->getId() . "\n";
        }
        $projectPerson = $person->getProjectPerson(70);
        if ($projectPerson) echo 'PROJ 70 ' . $projectPerson->getProject()->getId() . "\n";
        else                echo "No Project Person for: 70\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
        $this->test3();
    }
}
