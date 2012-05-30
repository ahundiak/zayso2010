<?php
namespace Zayso\CoreBundle\Component\FormType\Schedule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

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
        
        // Customice the pick lost for each position
        $officials = $this->officials;
        
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
