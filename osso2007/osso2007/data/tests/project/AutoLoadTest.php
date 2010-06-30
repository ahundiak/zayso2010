<?php
class AutoLoadTest extends BaseProjectTest
{
    function exp($name)
    {
        return preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $name);
//      return preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\1 \\2', $name);
    }
    function load($name)
    {
        $path = NULL;
        $right = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $name);
        if ($name != $right) {
            $left = substr($name,0,strlen($name) - strlen($right));
            switch($right) {
                case 'Item':
                case 'Model':
                case 'Table':
                    $path = "models/{$left}Model.php";
                    return $path;
                    
                case 'Cont':
                case 'View':
                    $rightx = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $left);
                    if ($rightx != $left) {
                        $leftx = substr($left,0,strlen($left) - strlen($rightx));
                        $path = "mvc/$leftx/$name.php";
                        return $path;
                    }
                    break;
            }
        }
        return $path;
    }
    function testExp()
    {
        $this->assertEquals($this->exp('PhyTeamItem'), 'Item');    
        $this->assertEquals($this->exp('PhyTeamModel'),'Model');    
        $this->assertEquals($this->exp('PhyTeamTable'),'Table');
          
        $this->assertEquals($this->exp('PhyTeam_Table'),'PhyTeam_Table');    
        $this->assertEquals($this->exp('Phy_TeamTable'),'Phy_TeamTable');    
        $this->assertEquals($this->exp('aaa'),          'aaa');    
            
    }
    function testLoad()
    {
        $this->assertEquals(ProjectLoader::getPath('PhyTeamItem'), 'models/PhyTeamModel.php');    
        $this->assertEquals(ProjectLoader::getPath('PhyTeamModel'),'models/PhyTeamModel.php');    
        $this->assertEquals(ProjectLoader::getPath('PhyTeamTable'),'models/PhyTeamModel.php');
        
        $this->assertEquals(ProjectLoader::getPath('PhyTeamEditCont'), 'mvc/PhyTeam/PhyTeamEditCont.php');
        $this->assertEquals(ProjectLoader::getPath('PhyTeamListView'), 'mvc/PhyTeam/PhyTeamListView.php');    
        $this->assertEquals(ProjectLoader::getPath('PhyTeamListTpl'),  'mvc/PhyTeam/PhyTeamListTpl.php');
        
        $this->assertEquals(ProjectLoader::getPath('SchedDivListWebView'),  'mvc/SchedDiv/SchedDivListWebView.php');
        $this->assertEquals(ProjectLoader::getPath('SchedDivListWebTpl'),   'mvc/SchedDiv/SchedDivListWebTpl.php');
        $this->assertEquals(ProjectLoader::getPath('SchedDivListExcelView'),'mvc/SchedDiv/SchedDivListExcelView.php');    
        $this->assertEquals(ProjectLoader::getPath('SchedDivListExcelTpl'), 'mvc/SchedDiv/SchedDivListExcelTpl.php');    
           
    }
}
?>
