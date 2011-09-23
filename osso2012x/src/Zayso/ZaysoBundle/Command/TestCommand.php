<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zayso:test')
            ->setDescription('Test Routines')
        ;
    }

    // Does not work as expected
    protected function testArray($output)
    {
        $accountCreateData = array('userName' => 'User 2', 'userPass' => 'zzz');

        $ao = new ArrayObject($accountCreateData);
        $output->writeln('AO ');

    }
    protected function testUser()
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $user = new User($em);
        $user->load(10,10,52);

        $projectPerson = $user->getProjectPerson();
        $accountCreateData = $projectPerson->get('accountCreateData');

        echo 'User: ' . $user->getName() . ' ' . $accountCreateData['refBadge'] . "\n";
    }
    protected function initProject($output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        // Still need this to avoid integrity contstraint
        $query = $em->createQuery('DELETE ZaysoBundle:ProjectPerson item');
        $query->getResult();

        $query = $em->createQuery('DELETE ZaysoBundle:Project item');
        $query->getResult();

        $project = new Project();
        $project->setId(52);
        $project->setDesc1('AYSO National Games 2012');
        $project->setStatus('Active');
        $em->persist($project);

        $project = new Project();
        $project->setId(70);
        $project->setDesc1('AYSO Area 5C/F Fall 2011');
        $project->setStatus('Active');
        $em->persist($project);

        $em->flush();

    }
    protected function testAccount2007()
    {
        $accountManager = $this->getContainer()->get('account2007.manager');
        $account = $accountManager->checkAccount('ahundiak','zzz');

        if (!$account) die('no account');
        echo $account->getAccountUser() . "\n";

        $member = $account->getPrimaryMember();
        echo $member->getMemberName() . "\n";

        $person = $member->getPerson();
        if (!$person) die('no person');

        echo $person->getLastName() . "\n";
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testAccount2007();
        return;

        $this->initProject($output);
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $output->writeln('EM ' . get_class($em));

        // Still need this to avoid integrity contstraint
        $query = $em->createQuery('DELETE ZaysoBundle:AccountPerson item');
        $query->getResult();

        $query = $em->createQuery('DELETE ZaysoBundle:Account item');
        $query->getResult();

        $query = $em->createQuery('DELETE ZaysoBundle:PersonRegistered item');
        $query->getResult();

        $query = $em->createQuery('DELETE ZaysoBundle:Person item');
        $query->getResult();

        $account = new Account();
        $account->setUserName('user1');
        $account->setUserPass('pass1');
        $account->setStatus('test');

        $accountPerson = new AccountPerson();
        $accountPerson->setAccount($account);
        $accountPerson->setRelId(1);
        
        $em->persist($account);
        $em->flush();

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->findOneByUserName('user1');

        $output->writeln('REPO ' . get_class($accountRepo));
        $output->writeln('User ' . $account->getUserName());

        $members = $account->getMembers();
        foreach($members as $member)
        {
            // $em->remove($member);
        }
        //$em->remove($account);
        $em->flush();

        // Try the creation interface
        $accountCreateData = array(
            'userName'  => 'User 2',
            'userPass1' => 'zzz',
            'userPass2' => 'zzz',
            'firstName' => 'First',
            'lastName'  => 'Last',
            'email'     => 'ahundiak@gmail.com',
            'cellPhone' => '2564575943',
            'aysoid'    => '12345678',
            'region'    => 894,
            'projectId' => 52,
        );
        $account = $accountRepo->create($accountCreateData);
      //$account = $accountRepo->create($accountCreateData);
        if (is_array($account))
        {
            $errors = $account;
            print_r($errors);
            return;
        }
        $output->writeln('User ' . $account->getUserName());
        return;
    }
}
