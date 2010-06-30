<?php
class ImportTest extends BaseModelTest
{
    function sestMonroviaU8ScheduleImport()
    {
        $import = new MonroviaU8ScheduleImport($this->context);
        $import->update = TRUE;
        $count = $import->import('/ahundiak/misc/soccer2007/fall/schedule/Monrovia/MonroviaU08Schedule20070815.xml');
        
        $this->assertEquals($count,85);
    }
    function sestMonroviaScheduleImport()
    {
        $import = new MonroviaScheduleImport($this->context);
        $import->update = FALSE;
        $import->datex  = '20071118';
        $import->workSheetNames = array(
            'U10B' => 'U10B',
            'U10G' => 'U10G',
            'U12B' => 'U12B',
            'U12G' => 'U12G',
            'U14B' => 'U14B',
            'U14G' => 'U14G',
//            'U16G' => 'U16G',
//            'U19G' => 'U19G',
        );
        $count = $import->import('/ahundiak/misc/soccer2007/fall/schedule/Monrovia/MonroviaSchedule20070902.xml');
        
        echo "\nGame Count $count\n";
        // $this->assertEquals($count,0);
    }
    function sestMadisonScheduleImport()
    {
        $import = new MadisonScheduleImport($this->context);
        $import->update = FALSE;
        $import->datex  = '20070918';
        $import->workSheetNames = array(
            'U10C' => 'U10C',
            'U10G' => 'U10G',
            'U12C' => 'U12C',
            'U12G' => 'U12G',
            'U14C' => 'U14C',
            'U14G' => 'U14G',
            'U16G' => 'U16G',
            'U19G' => 'U19G',
        );
        $count = $import->import('/ahundiak/misc/soccer2007/fall/schedule/Madison/MadisonSchedule20070902.xml');
        
        echo "\nGame Count $count\n";
        // $this->assertEquals($count,0);
    }
    function testMadisonWinterScheduleImport()
    {
        $import = new MadisonWinterScheduleImport($this->context);
        $import->update = TRUE;
        $import->datex  = '20081231';
        $import->workSheetNames = array(
            'Schedule' => 'Schedule',
        );
        $count = $import->import('/ahundiak/misc/soccer2008/winter/schedule/Madison/All.xml');
    }
    function sestHazelGreenScheduleImport()
    {
        $import = new HazelGreenScheduleImport($this->context);
        $import->update = TRUE;
        $import->datex  = '20070918';
        $import->workSheetNames = array(
            'U16 Girls' => 'U16G',
            'U19 Girls' => 'U19G',
        );
        $count = $import->import('/ahundiak/misc/soccer2007/fall/schedule/HazelGreen/HazelGreenSchedule20070831.xml');
        
        echo "\nGame Count $count\n";
        // $this->assertEquals($count,0);
    }
    function sestScheduleExport()
    {
        $export = new RegionScheduleExport($this->context);
        $export->export2007Fall('/ahundiak/misc/soccer2007/fall/schedule/Monrovia/MonroviaSchedule20070904.xml');        
    }
    function sest()
    {
    }
}
?>
