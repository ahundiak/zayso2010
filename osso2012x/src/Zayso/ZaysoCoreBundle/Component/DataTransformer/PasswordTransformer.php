<?php
namespace Zayso\ZaysoCoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PasswordTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;

        return $value;

        return substr($value,0,3) . '.' . substr($value,3,3) . '.' . substr($value,6,4);
    }
    public function reverseTransform($value)
    {
        $value = trim($value);
        if (strlen($value) ==  0) return $value;
        if (strlen($value) == 32) return $value;
        return md5($value);
    }
}
?>
