<?php
namespace Zayso\CoreBundle\FormType\Schedule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

class RefAssignPersonSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FormFactoryInterface $factory, $officials)
    {
        $this->factory = $factory;
        $this->officials = $officials;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
    public function preSetData(DataEvent $event)
    {
        $eventPerson = $event->getData();
        $form        = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (!$eventPerson) return;
        
        $person   = $eventPerson->getPersonz();
        $personId = $person->getId();
        
        $statePickList = array
        (
            'RequestAssignment'   => 'Request Assignment',
            'RequestRemoval'      => 'Request Removal',
            'AssignmentRequested' => 'Assignment Requested',
            'AssignmentApproved'  => 'Assignment Approved',
        );
        $officialsPickList = array();
        //if ($personId) $officialsPickList[0] = 'Remove';
        //else           $officialsPickList[0] = 'Unassigned';
        
        if ($personId) $emptyValue = null;
        else 
        {
            $emptyValue = 'Select Your Name';
            $statePickList = array('RequestAssignment' => 'Request Assignment');
        }
        $matched = false;
        foreach($this->officials as $official)
        {
            $officialsPickList[$official->getId()] = $official->getPersonName();
            if ($official->getId() == $personId) $matched = true;
        }
        if ($personId && !$matched)
        {
            // Someone not in officials is currently assigned
            $officialsPickList = array($personId => $person->getPersonName());
            $emptyValue = false;
            $state = $eventPerson->getState();
            
            // Because of error in batch update
            if (!$state) $state = 'AssignmentRequested';
            
            if (isset($statePickList[$state])) $stateDesc = $statePickList[$state];
            else                               $stateDesc = $state;
            
            $statePickList = array($state => $stateDesc);
        }
        if ($personId && $matched)
        {
            $officialsPickList = array($personId => $person->getPersonName());
            $emptyValue = false;
            
            $statePickList = array
            (
                'RequestRemoval'      => 'Request Removal',
                'AssignmentRequested' => 'Assignment Requested',
                'AssignmentApproved'  => 'Assignment Approved',
            );
        }
        
        $form->add($this->factory->createNamed('personIdx','choice', null, array(
            'label'         => 'Person',
            'required'      => false,
            'empty_value'   => $emptyValue,
            'empty_data'    => null,
            'choices'       => $officialsPickList,
        )));
        
        // Mess with state
        $state = $eventPerson->getState();
        if (!$state) $state = 'RequestAssignment';
        $form->add($this->factory->createNamed('statex','choice', null, array(
            'label'         => 'State',
            'required'      => false,
            'empty_value'   => false,
            'empty_data'    => null,
            'choices'       => $statePickList,
        )));
        
        // Done
        return;
    }
}

class RefAssignPersonFormType extends AbstractType
{
    public function getName() { return 'sch_ref_assign_person'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zayso\CoreBundle\Entity\EventPerson',
        ));
    }    
    public function __construct($officials)
    {
        $this->officials = $officials;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'hidden');
        
        $builder->add('typeDesc', 'text', array(
            'attr'      => array('size' => 10),
            'read_only' => true,
            'required'  => false,
        ));
        
        // Need to do this subsrcribe nonsense to get the actual data
        $subscriber = new RefAssignPersonSubscriber($builder->getFormFactory(),$this->officials);
        $builder->addEventSubscriber($subscriber);
        return;
    }
}
class RefAssignFormType extends AbstractType
{
    protected $manager = null;
    
    public function getName() { return 'sch_ref_assign'; }

    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    
    protected $builder = null;
    
    protected $officialsPickList = array();
    
    public function setOfficials($officials)
    {
        $this->officials = $officials;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder->add('persons', 'collection', array('type' => new RefAssignPersonFormType($this->officials)));
    }
}
?>
