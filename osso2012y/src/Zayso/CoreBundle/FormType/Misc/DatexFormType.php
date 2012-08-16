<?php

namespace Zayso\CoreBundle\FormType\Misc;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DatexFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year', 'choice', array(
            'label'         => 'Year',
            'required'      => true,
            'choices'       => $this->yearPickList,
            'expanded'      => false,
            'multiple'      => false,
            'attr'          => array('class' => 'zayso-date-gen')
        ));
        $builder->add('month', 'choice', array(
            'label'         => 'Month',
            'required'      => true,
            'choices'       => $this->monthPickList,
            'expanded'      => false,
            'multiple'      => false,
            'attr'          => array('class' => 'zayso-date-gen')
        ));
        $builder->add('day', 'choice', array(
            'label'         => 'day',
            'required'      => true,
            'choices'       => $this->dayPickList,
            'expanded'      => false,
            'multiple'      => false,
            'attr'          => array('class' => 'zayso-date-gen')
        ));
        $builder->add('date','text',array(
            'label'    => 'Date',
            'required' => false,
            'attr'     => array('size' => 16, 'class' => 'zayso-date-desc')
         ));
    }
    
    public function getName()   { return 'datex'; }
    
    protected $yearPickList = array(
        '2016' => '2016', '2015' => '2015', '2014' => '2014', '2013' => '2013',
        '2012' => '2012', '2011' => '2011', '2010' => '2010', '2009' => '2009',
        '2008' => '2008', '2007' => '2007', '2006' => '2006', '2005' => '2005',
        '2004' => '2004', '2003' => '2003', '2002' => '2002', '2001' => '2001',
    );
    protected $monthPickList = array(
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
    );
    protected $dayPickList = array(
        '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06',
        '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12',
        '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
        '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24',
        '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
        '31' => '31',
    );
}

?>
