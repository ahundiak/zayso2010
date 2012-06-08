<?php
namespace Zayso\S5GamesBundle\Component\FormType\Admin\Schedule;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ScheduleListGameFormType extends AbstractType
{
    public function getName() { return 'schListGame'; }
    
    protected $em;
    protected $emName;

    public function __construct($em = null, $emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('pool',  'text', array('attr' => array('size' => 9)));
      //$builder->add('status','text', array('attr' => array('size' => 9)));
        $builder->add('status', 'choice', array(
            'label'   => 'Game Status',
            'choices' => $this->gameStatusPickList,
        ));
    }
    protected $gameStatusPickList = array
    (
        'Active'     => 'Active', 
        'InProgress' => 'In Progress',
        'Played'     => 'Played',
        
        'Suspended'  => 'Suspended',
        'Terminated' => 'Terminated',
        'Forfeited'  => 'Forfeited',
        
        'Cancelled'  => 'Cancelled', 
        'StormedOut' => 'Stormed Out',

    );
}
class ScheduleListFormType extends AbstractType
{
    public function getName() { return 'schList'; }
    
    protected $em;
    protected $emName;

    public function __construct($em = null, $emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('games','collection',array('type' => new ScheduleListGameFormType()));
    }
}
?>
