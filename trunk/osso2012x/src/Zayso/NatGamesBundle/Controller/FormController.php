<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

use Symfony\Component\Form\AbstractType as AbstractFormType;
use Symfony\Component\Form\FormBuilder;

use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;
use Zayso\ZaysoBundle\Entity\Person;

use Zayso\ZaysoBundle\Component\Form\Type\AccountType;
use Zayso\ZaysoBundle\Component\Form\Type\AccountPersonType;
use Zayso\ZaysoBundle\Component\Form\Type\PersonType;

use Zayso\ZaysoBundle\Component\Debug;

class Task
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('task', new NotBlank());

        $metadata->addPropertyConstraint('dueDate', new NotBlank());
        $metadata->addPropertyConstraint('dueDate', new Type('\DateTime'));
    }
    protected $task;

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
    public function setDueDate(\DateTime $dueDate = null)
    {
        $this->dueDate = $dueDate;
    }
}
class TaskFormType extends AbstractFormType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('task');
        $builder->add('dueDate', null, array('widget' => 'single_text'));
    }

    public function getName()
    {
        return 'task';
    }
}

class FormController extends BaseController
{
    public function formAction(Request $request)
    {
        $account = new Account();
        $account->setUserName('uname');
        $account->setUserPass('upass');
        $account->setStatus  ('ac status');

        $person = new Person();
        $person->setNickName('Hondo');

        $accountPerson = new AccountPerson();
        $accountPerson->setStatus('ap status');
        $accountPerson->setAccount($account);
        $accountPerson->setPerson ($person);
        
        $form = $this->createForm(new AccountType(), $account);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            // $refBadge = $form['refBadge']->getData(); die($refBadge);
            
            if ($form->isValid())
            {
                // perform some action, such as saving the task to the database

                return $this->redirect($this->generateUrl('_natgames_form'));
            }
            // else die('Not Valid');
        }

        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        //Debug::dump($tplData['form']); die();
        return $this->render('NatGamesBundle:Form:account.html.twig', $tplData);
    }
    public function formTaskAction(Request $request)
    {
       // create a task and give it some dummy data for this example
        $task = new Task();
        $task->setTask('Write a blog post 2');
        $task->setDueDate(new \DateTime('tomorrow'));

        /*
        $form = $this->createFormBuilder($task)
            ->add('task',    'text', array('required' => false))
            ->add('dueDate', 'date', array('required' => false))
            ->getForm(); */

        $form = $this->createForm(new TaskFormType(), $task);


        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            //die('Task' . $task->getTask());

            if ($form->isValid())
            {
                // perform some action, such as saving the task to the database

                return $this->redirect($this->generateUrl('_natgames_form'));
            }
            // else die('Not Valid');
        }

        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Form:form.html.twig', $tplData);
    }
}
