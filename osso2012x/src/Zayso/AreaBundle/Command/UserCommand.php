<?php

namespace Zayso\AreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:user')
            ->setDescription('User Tests')
        ;
    }
    protected function test1()
    {
        $provider = $this->getContainer()->get('zayso_core.user.provider');
        $user = $provider->loadUserByUsername('ahundiak');
        echo 'User ' . get_class($user) . "\n";
        echo 'User ' . $user->getName() . "\n";

    }
    protected function test2()
    {
        $accountManager = $this->getContainer()->get('zayso_osso2007.account.manager');
        $account = $accountManager->checkAccount('ahundiak','');
        $accountPerson = $account->getPrimaryMember();
        $person = $accountPerson->getPerson();

        echo 'Account2007 ' .
            $account->getAccountUser() . ' ' .
            $account->getAccountName() . ' ' .
            $accountPerson->getMemberName() . '' .
            "\n";

        echo 'Person ' .
            $person->getFirstName() . ' ' .
            $person->getLastName()  . ' ' .
            $person->getRegionKey() . ' ' .
            $person->getAysoid() .
            "\n";

        $openids = $account->getOpenids();
        $openid = $openids[0];
        echo 'Openid ' . $openid->getIdentifier() . "\n";

        $volManager = $this->getContainer()->get('zayso_eayso.vol.manager');
        $vol = $volManager->loadVolCerts($person->getAysoid());
        echo 'Vol ' . $vol->getMemYear() . ' ' . $vol->getRefBadgeDesc() . "\n";

    }
    protected function test3()
    {
        $accountManager = $this->getContainer()->get('zayso_area.account.manager');
        $accountPerson = $accountManager->newAccountPerson(array());
        $accountPerson = $accountManager->getAccountPerson(array('accountId' => 1));
        $person = $accountPerson->getPerson();

        $this->dumpAccount($accountPerson->getAccount());
    }
    protected function test4()
    {
        $accountManager = $this->getContainer()->get('zayso_osso2007.account.manager');
        $account2007 = $accountManager->checkAccount('ahundiak','');

        $accountManager = $this->getContainer()->get('zayso_area.account.manager2007');
        $account = $accountManager->importAccount2007($account2007);
        $this->dumpAccount($account);

    }
    protected function test5()
    {
        echo "===== Test 5 =====\n";
        $accountManager = $this->getContainer()->get('zayso_area.account.manager2007');

        $account2007 = $accountManager->checkAccount2007('ahundiak2007');
        echo $account2007 . "\n";

        $account2007 = $accountManager->checkAccount2007('ahundiak','xxx');
        echo $account2007 . "\n";

        $account2007 = $accountManager->checkAccount2007('ahundiak');
        echo $account2007 . "\n";

        $account2007 = $accountManager->checkAccount2007('timholt@charter.net');
        if (!is_object($account2007)) echo $account2007 . "\n";
        else
        {
            $account = $accountManager->importAccount2007($account2007);
            $this->dumpAccount($account);
        }
    }
    protected function dumpAccount($account)
    {
        if (!$account)
        {
            echo "*** NULL Account\n";
            return;
        }
        $accountPerson = $account->getPrimaryMember();
        $person = $accountPerson->getPerson();
        if (!$person) { echo "No person yet\n"; return; }
        
        echo "====================\n";
        echo 'Person ' . $accountPerson->getPersonName() . ' ' . $person->getDob() . ' ' . $person->getGender() . "\n";

        echo 'VOL ' .
            $person->getAysoid()    . ' ' .
            $person->getOrgKey()    . ' ' .
            $person->getMemYear()   . ' ' .
            $person->getRefBadge()  . ' ' .
            $person->getRefDate()   . ' ' .
            $person->getSafeHaven() . ' ' .
            "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
        $this->test2();
        $this->test3();
      //$this->test4();
        $this->test5();
    }
}
