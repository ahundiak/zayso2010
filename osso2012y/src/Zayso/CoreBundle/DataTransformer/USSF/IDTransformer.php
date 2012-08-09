<?php
namespace Zayso\CoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class USSFIdTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return null;

        if (substr($value,0,4) == 'USSF') return substr($value,4);

        return $value;
    }
    public function reverseTransform($value)
    {
        $id = preg_replace('/\D/','',$value);
        
        if (!$id) return null;
        
        return 'USSF' . $id;
    }
}
?>
