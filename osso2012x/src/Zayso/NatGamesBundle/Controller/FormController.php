<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

use Symfony\Component\Form\AbstractType as AbstractFormType;
use Symfony\Component\Form\FormBuilder;

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
