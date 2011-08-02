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
        $accountCreateData = array('uname' => 'User 2', 'upass' => 'zzz');

        $ao = new ArrayObject($accountCreateData);
        $output->writeln('AO ');

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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
        $account->setUname('user1');
        $account->setStatus('test');

        $accountPerson = new AccountPerson();
        $accountPerson->setAccount($account);
        $accountPerson->setRelId(1);
        
        $em->persist($account);
        $em->flush();

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->findOneByUname('user1');

        $output->writeln('REPO ' . get_class($accountRepo));
        $output->writeln('User ' . $account->getUname());

        $members = $account->getMembers();
        foreach($members as $member)
        {
            // $em->remove($member);
        }
        //$em->remove($account);
        $em->flush();

        // Try the creation interface
        $accountCreateData = array(
            'uname'  => 'User 2',
            'upass1' => 'zzz',
            'upass2' => 'zzz',
            'fname'  => 'First',
            'lname'  => 'Last',
            'email'  => 'ahundiak@gmail.com',
            'aysoid' => '12345678',
            'region' => 894,
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
        $output->writeln('User ' . $account->getUname());
        return;
    }
}
