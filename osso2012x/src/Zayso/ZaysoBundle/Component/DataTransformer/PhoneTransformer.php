<?php
namespace Zayso\ZaysoBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PhoneTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;

        return substr($value,0,3) . '.' . substr($value,3,3) . '.' . substr($value,6,4);
    }
    public function reverseTransform($value)
    {
        return preg_replace('/\D/','',$value);
    }
}
?>
