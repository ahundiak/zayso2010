<?php
namespace Zayso\CoreBundle\Component\FormType\Person;

use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;

use Zayso\CoreBundle\Component\FormValidator\RegionValidator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AYSOEditFormType extends AbstractType
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function getName() { return 'aysoEdit'; }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('regKey','text', array('label' => 'AYSO ID (8-digits)', 'read_only' => true, 'attr' => array('size' => 10)));
        $builder->get('regKey')->appendClientTransformer(new AysoidTransformer());
 
        $builder->add('refBadge', 'choice', array(
            'label'         => 'AYSO Referee Badge',
            'required'      => true,
            'read_only'     => true,
            'choices'       => $this->refBadgePickList,
        ));
/*        
        $builder->add('refDate',   'text', array('label' => 'AYSO Referee Date',  'attr' => array('size' => 8),'required' => false,));

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
*/        
        $builder->add('orgKey','text', array('label' => 'AYSO Region Number', 'read_only' => true, 'attr' => array('size' => 4)));
        $builder->get('orgKey')->appendClientTransformer(new RegionTransformer());
        $builder->addValidator(new RegionValidator($this->manager->getEntityManager(),'orgKey'));
 
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
