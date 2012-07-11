<?php
namespace Zayso\EaysoBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//use Symfony\Component\Validator\Mapping\ClassMetadata;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    /** @Assert\NotBlank() */
    protected $task;

    /** @Assert\NotBlank() */
    /** @Assert\Type("\DateTime") */
    protected $dueDate;

    public function getTask()
    {
        return $this->task;
    }
    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }
    public function setDueDate($dueDate) //\DateTime $dueDate = null)
    {
        $this->dueDate = $dueDate;
    }
}

class EaysoManagerTest extends WebTestCase
{
    public function testvalidation()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $validator = $kernel->getContainer()->get('validator');

        $task = new Task();
        $task->setTask('Write a blog post 2');
        $task->setDueDate(new \DateTime('tomorrow'));

        $errors = $validator->validate($task);
        $this->assertEquals(0,count($errors));

        $task->setTask('');
        $errors = $validator->validate($task);
        $this->assertEquals(1,count($errors));
        $this->assertEquals('This value should not be blank',$errors[0]->getMessage());
        $this->assertEquals('Symfony\Component\Validator\ConstraintViolationList',get_class($errors));
        
        $task->setTask('Write a blog post 2');
        $task->setDueDate('Something');
        $errors = $validator->validate($task);
        $this->assertEquals(1,count($errors));
        $this->assertEquals('This value should be of type \DateTime',$errors[0]->getMessage());
        
    }
    public function testVolunteerCertifications()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $eaysoManager = $kernel->getContainer()->get('eayso.manager');

        $vol = $eaysoManager->loadVolCerts('AYSOV99437977');

        $this->assertEquals('Hundiak',         $vol->getLastName());
        $this->assertEquals('Advanced Referee',$vol->getRefBadgeDesc());
        
    }
}


?>
