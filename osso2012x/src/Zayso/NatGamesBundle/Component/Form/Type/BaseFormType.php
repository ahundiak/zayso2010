<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\NatGamesBundle\Component\Form\Type;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;
use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class BaseFormType extends AbstractType
{
    protected $name = 'base';
    protected $group = null;

    public function __construct($em = null)
    {
        $this->em = $em;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array();

        if ($this->group) $defaultOptions['validation_groups'] = array($this->group);

        return $defaultOptions;
    }
}
?>
