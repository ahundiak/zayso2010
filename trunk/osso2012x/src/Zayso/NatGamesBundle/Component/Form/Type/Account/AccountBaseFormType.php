<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\NatGamesBundle\Component\Form\Type\Account;

use Zayso\ZaysoBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\AysoidTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\RegionTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoBundle\Component\Form\Validator\UserNameValidator;

//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

class AccountBaseFormType extends AbstractType
{
    protected $name = 'accountBase';
    protected $group = null;

    public function __construct($em)
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
        '2011' => 'MY2011',
        '2012' => 'MY2012',
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
?>
