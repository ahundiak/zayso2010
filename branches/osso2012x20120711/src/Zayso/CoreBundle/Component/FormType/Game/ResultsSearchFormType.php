<?php
namespace Zayso\CoreBundle\Component\FormType\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;

class ResultsSearchFormType extends AbstractType
{
    protected $name = 'resultsSearch';
    public function getName() { return $this->name; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    protected $em;
    
    protected function getEntityManager() { return $this->em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {   
        $builder->add('div', 'choice', array(
            'label'   => 'Division',
            'choices' => $this->divPickList,
        ));       
        $builder->add('pool', 'choice', array(
            'label'   => 'Pool',
            'choices' => array(0 => 'Pool', 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D',),
        ));       
        $builder->addValidator(new CallbackValidator(function($form)
        {
            if(!$form['div']->getData())
            {
                $form['div']->addError(new FormError('Need to pick a division'));
            }
        }));
    }
    protected $divPickList = array
    (
        '0'    => 'Division',
        'U10B' => 'U10 Boys',
        'U10G' => 'U10 Girls',
        'U12B' => 'U12 Boys',
        'U12G' => 'U12 Girls',
        'U14B' => 'U14 Boys',
        'U14G' => 'U14 Girls',
        'U16B' => 'U16 Boys',
        'U16G' => 'U16 Girls',
        'U19B' => 'U19 Boys',
        'U19G' => 'U19 Girls',
    );
}
?>
