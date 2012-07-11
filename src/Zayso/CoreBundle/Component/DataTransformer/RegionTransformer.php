<?php
namespace Zayso\CoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class RegionTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;

        if (substr($value,0,5) == 'AYSOR') return (int)substr($value,5);

        return $value;
        return substr($value,0,3) . '.' . substr($value,3,3) . '.' . substr($value,6,4);
    }
    public function reverseTransform($value)
    {
        $region = (int)preg_replace('/\D/','',$value);
        if (!$region) return null;
        
        return sprintf('AYSOR%04u',$region);
    }
}
?>
