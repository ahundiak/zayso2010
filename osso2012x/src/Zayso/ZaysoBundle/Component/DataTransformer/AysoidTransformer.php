<?php
namespace Zayso\ZaysoBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class AysoidTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;

        if (substr($value,0,5) == 'AYSOV') return substr($value,5);

        return $value;
    }
    public function reverseTransform($value)
    {
        $aysoid = (int)preg_replace('/\D/','',$value);
        return 'AYSOV' . $aysoid;
    }
}
?>
