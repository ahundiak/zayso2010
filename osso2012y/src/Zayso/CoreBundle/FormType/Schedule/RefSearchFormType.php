<?php
namespace Zayso\CoreBundle\FormType\Schedule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RefSearchFormType extends AbstractType
{
    public function getName() { return 'ref_sch_search'; }
    
    protected $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function setTeams($teams)     { $this->teams   = $teams; }
    public function setPersons($persons) { $this->persons = $persons; }
    
    protected $days = array
    (
        'ALL' => 'All', 
        '20120704' => 'Wen', 
        '20120705' => 'Thu', 
        '20120706' => 'Fri', 
        '20120707' => 'Sat', 
        '20120708' => 'Sun'
    );
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Check Box
        $builder->add('dows', 'choice', array(
            'label'         => 'Days of Week',
            'required'      => true,
                               // Fri = Label, FRI keys/value
            'choices'       => $this->days,
            'expanded'      => true,
            'multiple'      => true,
        ));
        $builder->add('genders', 'choice', array(
            'label'         => 'Genders',
            'required'      => true,
            'choices'       => array('ALL' => 'All', 'B' => 'Boys', 'G' => 'Girls'),
            'expanded'      => true,
            'multiple'      => true,
        ));
        $builder->add('ages', 'choice', array(
            'label'         => 'Ages',
            'required'      => true,
            'choices'       => array(
                'ALL' => 'All', 
                'VIP' => 'VIP', 'U05' => 'U05', 'U06' => 'U06', 'U07' => 'U07', 'U08' => 'U08', 
                'U10' => 'U10', 'U12' => 'U12', 'U14' => 'U14', 
                'U16' => 'U16', 'U19' => 'U19',
            ),
            'expanded'      => true,
            'multiple'      => true,
        ));
        $builder->add('regions', 'choice', array(
            'label'         => 'Regions',
            'required'      => true,
            'choices'       => $this->regions,
            'expanded'      => true,
            'multiple'      => true,
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
        $builder->add('date1x','datex');
        $builder->add('date2x','datex');

        $builder->add('coach','text',array(
            'label'    => 'Filter Coach ',
            'required' => false,
            'attr'     => array('size' => 30)
         ));
        $builder->add('official','text',array(
            'label'    => 'Filter Referee ',
            'required' => false,
            'attr'     => array('size' => 30)
        ));
        $builder->add('teamIds', 'choice', array(
            'label'         => 'My Teams',
            'required'      => true,
            'choices'       => $this->teams,
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'zayso-checkbox-all'),
            'disabled'      => false,
        ));
        $builder->add('personIds', 'choice', array(
            'label'         => 'My People',
            'required'      => true,
            'choices'       => $this->persons,
            'expanded'      => true,
            'multiple'      => true,
            'attr' => array('class' => 'zayso-checkbox-all'),
            'disabled'      => false,
        ));
    }
    protected $times = array(
        '0600' => '06 AM', '0700' => '07 AM', '0800' => '08 AM', '0900' => '09 AM',
        '1000' => '10 AM', '1100' => '11 AM', '1200' => '12 PM', '1300' => '01 PM',
        '1400' => '02 PM', '1500' => '03 PM', '1600' => '04 PM', '1700' => '05 PM',
        '1800' => '06 PM', '1900' => '07 PM', '2000' => '08 PM', '2100' => '09 PM',
    );
    protected $regions = array(
        'All'       => 'All', 
        'AYSOR0160' => 'Hunt', // HSV
        'AYSOR0778' => 'Hart', // Hartselle
        'AYSOR0498' => 'Madi', // Mad
        'AYSOR0773' => 'Arab', // Arab
        'AYSOR0894' => 'Monr', // Mon
        'AYSOR0914' => 'East', // EL
        'AYSOR1174' => 'NEMC', // NEMC
        'AYSOR9991' => 'Exca', // Excalibur
    );
}
?>
