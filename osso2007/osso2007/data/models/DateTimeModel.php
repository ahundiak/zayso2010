<?php
class DateTimeModel extends BaseModel
{
    const TIME_TYPE_TBD    = 'TB';
    const TIME_TYPE_BYE_NG = 'BN';
    const TIME_TYPE_BYE_WG = 'BW';
    
    function getToday()
    {
        $date = time();
        return date('Ymd',$date);    
    }
    function getNextSunday()
    {
        $date = time();
        $secondsPerDay = 60 * 60 * 24;
        
        if (date('w',$date) == 0) $date += $secondsPerDay;
        
        // Go to sunday
        while(date('w',$date) != 0) $date += $secondsPerDay;
        
        // Skip a week
        $date += ($secondsPerDay * 7);
        
        return date('Ymd',$date);
    }
	function getYearPickList()
	{
		return array(
                    '2011' => '2011',
      '2010' => '2010',
		  '2009' => '2009',
      '2008' => '2008',
			'2007' => '2007',
      '2006' => '2006',
			'2005' => '2005',
			'2004' => '2004',
			'2003' => '2003',
			'2002' => '2002',
		);
	}
	function getMonthPickList()
	{
		return array(
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec',
		);
	}
	function getDayPickList()
	{
		return array(
			'01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05',
			'06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10',
			'11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15',
			'16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20',
			'21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25',
			'26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30',
			'31'=>'31',
		);
	}
    function getHourPickList()
    {
        return array(
            '06' => ' 6:00 AM',
            '07' => ' 7:00 AM',
            '08' => ' 8:00 AM',
            '09' => ' 9:00 AM',
            '10' => '10:00 AM',
            '11' => '11:00 AM',
            '12' => '12:00 PM Noon',
            '13' => ' 1:00 PM',
            '14' => ' 2:00 PM',
            '15' => ' 3:00 PM',               
            '16' => ' 4:00 PM',
            '17' => ' 5:00 PM',
            '18' => ' 6:00 PM',
            '19' => ' 7:00 PM',
            '20' => ' 8:00 PM',
            '21' => ' 9:00 PM',
            'BN' => 'BYE No Game',
            'BW' => 'BYE Want Game',
            'TB' => 'TBD',
            '22' => '10:00 PM',
            '23' => '11:00 PM',
            '00' => '12:00 AM Mid',
            '01' => ' 1:00 AM',
            '02' => ' 2:00 AM',
            '03' => ' 3:00 AM',
            '04' => ' 4:00 AM',
            '05' => ' 5:00 AM',
        );
    }
    function getMinutePickList()
    {
        return array(
            '00' => '00',
            '15' => '15',
            '30' => '30',
            '45' => '45',
            '05' => '05',
            '10' => '10',
            '20' => '20',
            '25' => '25',
            '35' => '35',
            '40' => '40',
            '50' => '50',
            '55' => '55',
        );
    }
    function getDurationPickList()
    {
        return array(
             '60' =>  '60',
             '75' =>  '75',
             '90' =>  '90',
            '120' => '120',
        );
    }
    function getDateFromExcelFormat($dtg)
    {
        return substr($dtg,0,4) . substr($dtg,5,2) . substr($dtg,8,2);
    }
    function getTimeFromExcelFormat($dtg)
    {
        return substr($dtg,11,2) . substr($dtg,14,2);
    }
    function getTimeFromAmPmFormat($dtg)
    {
        // Start with am/pm stuff
        $am = stristr($dtg,'am');
        $pm = stristr($dtg,'pm');
        if (!$am && !$pm) return NULL;
        if ($am) $dtg = substr($dtg,0,strlen($dtg)-strlen($am));
        if ($pm) $dtg = substr($dtg,0,strlen($dtg)-strlen($pm));
   
        // Hour and minutes
        $numbers = explode(':',$dtg);
        if (count($numbers) != 2) return NULL;
        $hour   = (int)$numbers[0];
        $minute = (int)$numbers[1];
        
        if ($pm) {
            $hour += 12;
            if ($hour == 24) $hour = 12;
        }
        
        // The final format
        $dtg = sprintf("%02d%02d",$hour,$minute);
        
        return $dtg;
    }
}  
?>
