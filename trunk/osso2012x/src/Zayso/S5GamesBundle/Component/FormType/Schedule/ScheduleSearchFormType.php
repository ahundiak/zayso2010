<?php
namespace Zayso\S5GamesBundle\Component\FormType\Schedule;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class ScheduleSearchFormType extends AbstractType
{
    protected $name = 'schSearch';
    public function getName() { return $this->name; }
    
    protected $em;
    protected $emName;

    public function __construct($em = null, $emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        // Check Box
        $builder->add('dows', 'choice', array(
            'label'         => 'Days of Week',
            'required'      => true,
                               // Fri = Label, FRI keys/value
            'choices'       => array('ALL' => 'All', '20120615' => 'Fri', '20120616' => 'Sat', '20120617' => 'Sun'),
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'zayso-checkbox-all'),
        ));
        $builder->add('genders', 'choice', array(
            'label'         => 'Genders',
            'required'      => true,
            'choices'       => array('ALL' => 'All', 'B' => 'Boys', 'G' => 'Girls'),
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'zayso-checkbox-all'),
        ));
        $builder->add('ages', 'choice', array(
            'label'         => 'Ages',
            'required'      => true,
            'choices'       => array(
                'ALL' => 'All', 
                'U10' => 'U10', 'U12' => 'U12', 'U14' => 'U14', 
                'U16' => 'U16', 'U19' => 'U19',
            ),
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'zayso-checkbox-all'),
        ));
        $builder->add('time1', 'choice', array(
            'label'         => 'Time 1',
            'required'      => true,
            'choices'       => $this->times,
            'expanded'      => false,
            'multiple'      => false,
        ));
        $builder->add('time2', 'choice', array(
            'label'         => 'Time 2',
            'required'      => true,
            'choices'       => $this->times,
            'expanded'      => false,
            'multiple'      => false,
        ));

    }
    protected $times = array(
        '0600' => '06 AM', '0700' => '07 AM', '0800' => '08 AM', '0900' => '09 AM',
        '1000' => '10 AM', '1100' => '11 AM', '1200' => '12 PM', '1300' => '01 PM',
        '1400' => '02 PM', '1500' => '03 PM', '1600' => '04 PM', '1700' => '05 PM',
        '1800' => '06 PM', '1900' => '07 PM', '2000' => '08 PM', '2100' => '09 PM',
    );
}
?>
