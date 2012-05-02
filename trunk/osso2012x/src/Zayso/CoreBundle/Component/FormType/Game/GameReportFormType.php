<?php
namespace Zayso\CoreBundle\Component\FormType\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class GameTeamReportFormType extends AbstractType
{
    protected $name = 'team';
    public function getName() { return $this->name; }
    
    public function __construct($em,$emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    protected $em;
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('type', 'text', array(
            'attr' => array('size' => 6),
            'read_only' => true,
        ));
        $builder->add('teamKey', 'text', array(
            'attr' => array('size' => 30),
            'read_only' => true,
        ));
        $builder->add('goalsScored', 'integer', array(
            'attr' => array('size' => 4),
            'required' => true,
            'error_bubbling' => true,
            'invalid_message' => 'Goals scored must be a number',
        ));
        $builder->add('cautions', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('sendoffs', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('sportsmanship', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
         $builder->add('fudgeFactor', 'integer', array(
            'attr' => array('size' => 4),
            'required' => false,
        ));
        $builder->add('pointsEarned', 'integer', array(
            'attr' => array('size' => 4),
            'required'  => false,
            'read_only' => true,
        ));
    }
}
class GameReportFormType extends AbstractType
{
    protected $name = 'gameReport';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'games';
    
    protected function getEntityManager() { return $this->em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('num',      'text', array('read_only' => true, 'label' => 'Number'));
        $builder->add('type',     'text', array('read_only' => true, 'label' => 'Type'));
        $builder->add('date',     'text', array('read_only' => true, 'label' => 'Date'));
        $builder->add('time',     'text', array('read_only' => true, 'label' => 'Time'));
        $builder->add('pool',     'text', array('read_only' => true, 'label' => 'Pool', 'required' => false));
        $builder->add('fieldDesc','text', array('read_only' => true, 'label' => 'Field','required' => false));
        
        $builder->add('status', 'choice', array(
            'label'   => 'Game Status',
            'choices' => $this->statusPickList,
        ));
        
        $builder->add('teams', 'collection', array('type' => new GameTeamReportFormType($this->em)));
        
        $builder->add('report', 'textarea', array('label' => 'Comments', 'required' => false, 
            'attr' => array('rows' => 12, 'cols' => 99, 'wrap' => 'hard', 'class' =>'textarea')));
        
        $builder->add('reportStatus', 'text', array('read_only' => true, 'label' => 'Report Status', 'required' => false));
        
        $builder->get('report')->appendClientTransformer(new StripTagsTransformer());
        
    }
    protected $statusPickList = array('Active' => 'Active', 'Cancelled' => 'Cancelled', 'Processed' => 'Processed');
}
?>
