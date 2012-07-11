<?php
namespace Zayso\CoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class UssfidTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // die('aysoid ' . '"' . $value . '"');
        
        if (!$value) return '';

        if (substr($value,0,5) == 'USSFR') return substr($value,5);

        return $value;
    }
    public function reverseTransform($value)
    {        
        $ussfid = preg_replace('/\D/','',$value);
        if (!$ussfid) return '';
        return 'USSFR' . $ussfid;
    }
}
?>
