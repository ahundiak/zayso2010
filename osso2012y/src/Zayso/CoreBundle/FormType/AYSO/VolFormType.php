<?php
namespace Zayso\CoreBundle\FormType\AYSO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class VolFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function getName  () { return 'zayso_core_ayso_vol'; }
    public function getParent() { return 'form'; }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zayso\CoreBundle\Entity\PersonReg'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $regex = new Regex(array('pattern' => '/^(AYSOV)?\d{8}$/'));
        
        $builder->add('regKey','zayso_core_ayso_vol_id',array('constraints' => array(new NotBlank(),$regex)));
        
        $builder->add('org',   'zayso_core_ayso_region');
  
        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'choices'       => $this->refBadgePickList,
        ));
        $builder->add('refDate','text', array('label' => 'AYSO Referee Date',  'attr' => array('size' => 8),'required' => false,));

        $builder->add('safeHaven', 'choice', array(
            'label'         => 'AYSO Safe Haven',
            'required'      => true,
            'choices'       => $this->safeHavenPickList,
        ));
        $builder->add('memYear', 'choice', array(
            'label'         => 'AYSO Vol Year',
            'required'      => true,
            'choices'       => $this->memYearPickList,
        ));
    }
    protected $refBadgePickList = array
    (
        'None'         => 'None',
        'Regional'     => 'Regional',
        'Intermediate' => 'Intermediate',
        'Advanced'     => 'Advanced',
        'National'     => 'National',
        'National 2'   => 'National 2',
        'Assistant'    => 'Assistant',
        'U8 Official'  => 'U8',
    );
    protected $safeHavenPickList = array
    (
        'None'    => 'None',
        'Yes'     => 'Yes',
        'AYSO'    => 'AYSO',
        'Coach'   => 'Coach',
        'Referee' => 'Referee',
        'Youth'   => 'Youth',
    );
    protected $genderPickList = array
    (
        'N' => 'None',
        'M' => 'Male',
        'F' => 'Female',
    );
    protected $memYearPickList = array
    (
        'None' => 'None',
        '2012' => 'MY2012',
        '2011' => 'MY2011',
        '2010' => 'MY2010',
        '2009' => 'FS2009',
        '2008' => 'FS2008',
        '2007' => 'FS2007',
        '2006' => 'FS2006',
        '2005' => 'FS2005',
        '2004' => 'FS2004',
        '2003' => 'FS2003',
        '2002' => 'FS2002',
        '2001' => 'FS2001',
    );
}
