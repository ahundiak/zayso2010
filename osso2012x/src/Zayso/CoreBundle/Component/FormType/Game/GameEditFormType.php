<?php
namespace Zayso\CoreBundle\Component\FormType\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class GameEditFormTypexxx extends AbstractType
{
    protected $name = 'gameEdit';
    public function getName() { return $this->name; }
    
    public function __construct($em,$emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    protected $em;
}
class GameTeamEditFormType extends AbstractType
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
        $builder->add('key', 'text', array(
            'attr' => array('size' => 30),
            'read_only' => true,
        ));
        $builder->add('desc1', 'text', array(
            'attr' => array('size' => 30),
        ));
    }
}
class GameTeamRelEditFormType extends AbstractType
{
    protected $name = 'teamRel';
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
        $builder->add('team', new GameTeamEditFormType($this->em));
    }
}
class GameEditFormType extends AbstractType
{
    protected $name = 'gameEdit';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    protected $emName = 'games';
    
    protected function getEntityManager() { return $this->em; }
    
    protected $builder = null;
    
    protected function addText($name,$label,$size=20)
    {
       $this->builder->add($name,'text', array
       (
           'read_only' =>  true, 
           'required'  => false,
           'label'     => $label,
           'attr'      => array('size' => $size),

        ));
         
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->builder = $builder;
        
        $this->addText('num',      'Number',6);
        $this->addText('type',     'Type',  6);
        $this->addText('date',     'Date',  8);
        $this->addText('time',     'Time',  6);
      //$this->addText('pool',     'Pool', 10);
        $this->addText('fieldDesc','Field',12);
        
        $this->builder->add('pool','text', array
        (
           'label'     => 'Pool',
           'attr'      => array('size' => 12),
        ));

        $builder->add('teams', 'collection', array('type' => new GameTeamRelEditFormType($this->em)));       
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
?>
