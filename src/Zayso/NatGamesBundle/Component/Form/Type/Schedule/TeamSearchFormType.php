<?php
namespace Zayso\NatGamesBundle\Component\Form\Type\Schedule;

use Doctrine\ORM\EntityRepository;
use Zayso\CoreBundle\Repository\TeamRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class TeamSearchFormType extends AbstractType
{
    protected $name = 'teamSchSearch';
    public function getName() { return $this->name; }
    
    protected $manager;

    public function __construct($manager = null)
    {
        $this->manager = $manager;
    }
    protected $days = array
    (
        'ALL' => 'All', 
        '20120705' => 'Thu', 
        '20120706' => 'Fri', 
        '20120707' => 'Sat', 
        '20120708' => 'Sun'
    );
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        // Check Box
        $builder->add('dows', 'choice', array(
            'label'         => 'Days of Week',
            'required'      => true,
                               // Fri = Label, FRI keys/value
            'choices'       => $this->days,
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
        
        $manager = $this->manager;
        
        $teams = $manager->qbPhyTeamsForProject(52)->getQuery()->getResult();
        $teamsx = array();
        foreach($teams as $team)
        {
            $teamsx[$team->getId()] = $team->getDesc();
        }
        $builder->add('team1', 'choice', array(
            'label'         => 'Team 1',
            'empty_value'   => 'Select Team 1',
            'required'      => false,
            'choices'       => $teamsx,
            'expanded'      => false,
            'multiple'      => false,
        ));
        $builder->add('team2', 'choice', array(
            'label'         => 'Team 2',
            'empty_value'   => 'Select Team 2',
            'required'      => false,
            'choices'       => $teamsx,
            'expanded'      => false,
            'multiple'      => false,
        ));
        $builder->add('team3', 'choice', array(
            'label'         => 'Team 3',
            'empty_value'   => 'Select Team 3',
            'required'      => false,
            'choices'       => $teamsx,
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
