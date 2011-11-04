<?php
namespace Zayso\ZaysoBundle\Component\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class DateTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) return $value;
        return substr($value,4,2) . '/' . substr($value,6,2) . '/' . substr($value,0,4);
    }
    public function reverseTransform($date)
    {
        // $value = preg_replace('/\D/','',$value);
        $date = trim($date);
        if (!$date) return '';

        // YYYYMMDD
        $datex = preg_replace('/\D/','',$date);
        if (($datex == $date) && (strlen($datex) == 8)) return $datex;

        // MM/DD/YY or MM/DD/YYYY
        $parts = explode('/',$date);
        if (count($parts) == 3)
        {
            $year = (int)$parts[2];
            if ($year < 100)
            {
                if ($year > 20) $year += 1900;
                else            $year += 2000;
            }
            $datex = sprintf('%04d%02d%02d',$year,(int)$parts[0],(int)$parts[1]); // die($datex);
            return $datex;
        }
       die('Date Transform: ' . $date);

    }
}
?>
