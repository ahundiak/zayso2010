<?php
namespace Zayso\CoreBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimeTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;
        return substr($value,0,2) . ':' . substr($value,2,2);
    }
    public function reverseTransform($time)
    {
        $time = trim($time);
        if (!$time) return '';

        if ($time == 'TBD') return $time;
        if ($time == 'BYE') return $time;

        $parts = explode(':',$time);
        if (count($parts) == 2)
        {
            $time = sprintf('%02u%02u',$parts[0],$parts[1]);
            return $time;
        }
        die('Time Transform: ' . $time);
    }
}
?>
