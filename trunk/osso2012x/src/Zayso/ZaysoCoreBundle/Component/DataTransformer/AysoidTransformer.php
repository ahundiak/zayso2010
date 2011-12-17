<?php
namespace Zayso\ZaysoCoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class AysoidTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // die('aysoid ' . '"' . $value . '"');
        
        if (!$value) return '';

        if (substr($value,0,5) == 'AYSOV') return substr($value,5);

        return $value;
    }
    public function reverseTransform($value)
    {
        $aysoid = (int)preg_replace('/\D/','',$value);
        if (!$aysoid) return '';
        return 'AYSOV' . $aysoid;
    }
}
?>
