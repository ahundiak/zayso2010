<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;

class RefSchedSearchViewHelper
{
    protected $format = null;

    static public $yearPickList = array(
        '2016' => '2016', '2015' => '2015', '2014' => '2014', '2013' => '2013',
        '2012' => '2012', '2011' => '2011', '2010' => '2010', '2009' => '2009',
        '2008' => '2008', '2007' => '2007', '2006' => '2006', '2005' => '2005',
        '2004' => '2004', '2003' => '2003', '2002' => '2002', '2001' => '2001',
    );
    static public $monthPickList = array(
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
    );
    static public $dayPickList = array(
        '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06',
        '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12',
        '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
        '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24',
        '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
        '31' => '31',
    );
    static public $hourPickList = array(
        '06' => '06 AM', '07' => '07 AM', '08' => '08 AM', '09' => '09 AM',
        '10' => '10 AM', '11' => '11 AM', '12' => '12 PM', '13' => '01 PM',
        '14' => '02 PM', '15' => '03 PM', '16' => '04 PM', '17' => '05 PM',
        '18' => '06 PM', '19' => '07 PM', '20' => '08 PM', '21' => '09 PM',
    );
    public $sortByPickList = array(1 => 'Date,Time,Field', 2 => 'Date,Field,Time', 3 => 'Date,Age,Time');
    
    public $ages    = array('All' => 'All',
        'VIP' => 'VIP', 'U05' => 'U05', 'U06' => 'U06',
        'U07' => 'U07', 'U08' => 'U08', 'U10' => 'U10',
        'U12' => 'U12', 'U14' => 'U14', 'U16' => 'U16', 'U19' => 'U19');
    
    public $genders = array('All' => 'All','B' => 'Boys','G' => 'Girls','C' => 'Coed');
    
    public $regions = array('All' => 'All', 
        'AYSOR0160' => 'R0160',
        'AYSOR0498' => 'R0498',
        'AYSOR0894' => 'R0894',
        'AYSOR0914' => 'R0914',
        'AYSOR1174' => 'R1174');

    public function __construct($format)
    {
        $this->format = $format;
    }
    public function genAges($searchData)
    {
        return $this->genTableSet($searchData,'ages',$this->ages);
    }
    public function genGenders($searchData)
    {
        return $this->genTableSet($searchData,'genders',$this->genders);
    }
    public function genRegions($searchData)
    {
        return $this->genTableSet($searchData,'regions',$this->regions);
    }
    public function genTableSet($searchData,$name,$items)
    {
        if (isset($searchData[$name])) $searchValues = $searchData[$name];
        else                           $searchValues = array();
        
        $html = '<table border="1" style="margin: 0;"><tr>' . "\n";
        
        $first = true;
        foreach($items as $key => $content)
        {
            $html .= sprintf('<td width="40" align="center">%s<br />',$content) . "\n";
            
            if (isset($searchValues[$key]) && $searchValues[$key]) $checked = 'checked="checked"';
            else                                                   $checked = null;

            if ($first)
            {
                $first = false;
                $class = 'class="zayso-checkbox-all"';
            }
            else $class = null;
            
            $html .= sprintf('<input type="hidden"   name="refSchedSearchData[%s][%s]" value="0" />',$name,$key) . "\n";
            
            $html .= sprintf('<input type="checkbox" name="refSchedSearchData[%s][%s]" value="%s" %s %s />',
                    $name,$key,$key,$checked,$class) . "\n";
            
            $html .= '</td>' . "\n";
        }
        $html .= '</tr></table>' . "\n";
        return $html;
    }
    public function genSortByPickList($sortBy)
    {
        $name = 'refSchedSearchData';
        
        $html  = null;
        $html .= sprintf('<select name="%s[sortBy]" >',$name);
        $html .= $this->format->formOptions($this->sortByPickList,$sortBy);
        $html .= '</select>' . "\n";
        
        return $html;
    }
    public function genDate($name,$date)
    {
        $html = null;

        $html .= sprintf('<select class="zayso-date-gen" name="%s[year]" >',$name);
        $html .= $this->format->formOptions(self::$yearPickList,substr($date,0,4));
        $html .= '</select>' . "\n";

        $html .= sprintf('<select class="zayso-date-gen" name="%s[month]" >',$name);
        $html .= $this->format->formOptions(self::$monthPickList,substr($date,4,2));
        $html .= '</select>' . "\n";

        $html .= sprintf('<select class="zayso-date-gen" name="%s[day]" >',$name);
        $html .= $this->format->formOptions(self::$dayPickList,substr($date,6,2));
        $html .= '</select>' . "\n";

        $desc = $this->genDateDesc($date);
        $html .= sprintf('<input type="text" class="zayso-date-desc" name="%s[desc]" readonly="readonly" value="%s" />',$name,$desc);
        $html .= "\n";
       
        return $html;
    }
    public function genHour($name,$time)
    {
        $html = null;

        $html .= sprintf('<select name="%s[hour]" >',$name);
        $html .= $this->format->formOptions(self::$hourPickList,substr($time,0,2));
        $html .= '</select>' . "\n";

        return $html;
    }
    public function genDateDesc($date)
    {
        $date = sprintf('%s/%s/%s',substr($date,0,4),substr($date,4,2),substr($date,6,2));
        $date = new \DateTime($date);

        return $date->format('D M d Y');
    }
}
