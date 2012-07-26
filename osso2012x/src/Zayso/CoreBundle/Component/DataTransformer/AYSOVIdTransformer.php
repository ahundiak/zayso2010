<?php
namespace Zayso\CoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class AYSOVIdTransformer implements DataTransformerInterface
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
        $id = (int)preg_replace('/\D/','',$value);
        if (!$id) return '';
        return 'AYSOV' . $id;
    }
}
?>
