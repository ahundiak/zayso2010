<?php
namespace Zayso\CoreBundle\Component\FormType\Schedule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

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
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }
        $personId = $data['personId'];
        
        $officialsPickList = array();
        if ($personId) $officialsPickList[0] = 'Remove';
        else           $officialsPickList[0] = 'Unassigned';
        
        $matched = false;
        foreach($this->officials as $official)
        {
            $officialsPickList[$official->getId()] = $official->getPersonName();
            if ($official->getId() == $personId) $matched = true;
        }
        if ($personId && !$matched)
        {
            // Someone not in officials is currently assigned
            $officialsPickList = array($personId => $data['personName']);
        }
        $form->add($this->factory->createNamed('choice','personId', null, array(
            'label'         => 'Person',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $officialsPickList,
        )));
        return;
        
        // check if the product object is "new"
        if (!$data->getId()) {
            $form->add($this->factory->createNamed('text', 'name'));
        }
    }
}

class RefAssignPersonFormType extends AbstractType
{
    public function getName() { return 'refAssignPerson'; }
    
    public function __construct($officials)
    {
        $this->officials = $officials;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('type', 'hidden');
        
        $builder->add('typeDesc', 'text', array(
            'attr'      => array('size' => 10),
            'read_only' => true,
        ));
        
        // Need to do this subsrcribe nonsense to get the actual data
        $subscriber = new RefAssignPersonSubscriber($builder->getFormFactory(),$this->officials);
        $builder->addEventSubscriber($subscriber);
        return;
        
        
        // Customice the pick lost for each position
        $officials = $this->officials;
        
        // This does not work as expected
        //$person = $builder->getData();
        //echo 'Class ' . get_class($person);
        //print_r($person); print_r($options); die(' form');
        
        $builder->add('personId', 'choice', array(
            'label'         => 'Person',
            'required'      => false,
            'empty_value'   => false,
            'choices'       => $officials,
        ));
    }
}
class RefAssignFormType extends AbstractType
{
    protected $name = 'gameRefAssign';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'games';
    
    protected function getEntityManager() { return $this->em; }
    
    protected $builder = null;
    
    protected $officialsPickList = array();
    
    public function setOfficials($officials)
    {
        $this->officials = $officials;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {     
        $builder->add('persons', 'collection', array('type' => new RefAssignPersonFormType($this->officials)));
        
    }
}
?>
