<?php echo "<?xml version=\"1.0\"?>\n" ?>
<?php echo "<?mso-application progid=\"Excel.Sheet\"?>\n" ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Art Hundiak</Author>
  <LastAuthor>Art Hundiak</LastAuthor>
  <Created>2008-08-19T13:49:34Z</Created>
  <Company>Intergraph Solutions Group</Company>
  <Version>11.5606</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>4110</WindowHeight>
  <WindowWidth>19035</WindowWidth>
  <WindowTopX>240</WindowTopX>
  <WindowTopY>90</WindowTopY>
  <ActiveSheet>1</ActiveSheet>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s21">
   <Alignment ss:Vertical="Bottom" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Interior ss:Color="#CCFFCC" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Teams">
  <Table 
    ss:ExpandedColumnCount="15" 
    ss:ExpandedRowCount="<?php echo count($this->teams) + 1; ?>" 
    x:FullColumns="1"
    x:FullRows="1">
   <Column ss:Width="200"/>
   <Column ss:StyleID="s23" ss:AutoFitWidth="0" ss:Width="60"/>
   <Column ss:StyleID="s23" ss:AutoFitWidth="0" ss:Width="60"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>
   <Row ss:Height="25.5">
    <Cell><Data ss:Type="String">Team</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Points&#10;Processed</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Number&#10;Referees</Data></Cell>
    <Cell><Data ss:Type="String">Referee 1</Data></Cell>
    <Cell><Data ss:Type="String">Refreee 2</Data></Cell>
    <Cell><Data ss:Type="String">Referee 3</Data></Cell>
    <Cell><Data ss:Type="String">Referee 4</Data></Cell>
    <Cell><Data ss:Type="String">Referee 5</Data></Cell>
    <Cell><Data ss:Type="String">Referee 6</Data></Cell>
    <Cell><Data ss:Type="String">Referee 7</Data></Cell>
    <Cell><Data ss:Type="String">Referee 8</Data></Cell>
    <Cell><Data ss:Type="String">Referee 9</Data></Cell>
    <Cell><Data ss:Type="String">Referee 10</Data></Cell>
    <Cell><Data ss:Type="String">Referee 11</Data></Cell>
    <Cell><Data ss:Type="String">Referee 12</Data></Cell>
   </Row>
<?php foreach ($this->teams as $team) { ?>
   <Row ss:Height="25.5">
    <Cell><Data ss:Type="String"><?php echo $team->desc; ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $team->points->team; ?></Data></Cell>
    <Cell <?php if (count($team->referees) >= 3) echo 'ss:StyleID="s25"'; ?>>
      <Data ss:Type="Number"><?php echo count($team->referees); ?></Data>
    </Cell>
<?php foreach($team->referees as $referee) { ?>
    <Cell ss:StyleID="s21">
      <Data ss:Type="String"><?php echo $referee->name1 . '&#10;' .
                'Pri '   . $referee->getTeamPri($team) .
              ', Max '   . $referee->getTeamMax($team) .
              ', Proc: ' . $referee->points->processed .
              ', Team: ' . $referee->getTeamAct($team); ?>
      </Data>
    </Cell>
<?php } ?>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>13</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Referees">
  <Table 
    ss:ExpandedColumnCount="8" 
    ss:ExpandedRowCount="<?php echo count($this->referees) + 1; ?>" 
    x:FullColumns="1"
    x:FullRows="1">
   <Column ss:AutoFitWidth="0" ss:Width="100"/>
   <Column ss:AutoFitWidth="0" ss:Width="100"/>
   <Column ss:AutoFitWidth="0" ss:Width="30"/>
   <Column ss:StyleID="s23" ss:AutoFitWidth="0" ss:Width="60.0"/>
   <Column ss:StyleID="s23" ss:AutoFitWidth="0" ss:Width="60.0"/>
   <Column ss:AutoFitWidth="0" ss:Width="200"/>
   <Column ss:AutoFitWidth="0" ss:Width="200"/>
   <Column ss:AutoFitWidth="0" ss:Width="200"/>
   <Row ss:Height="25.5">
    <Cell><Data ss:Type="String">Referee Name</Data></Cell>
    <Cell><Data ss:Type="String">Referee Name</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">Age</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Points&#10;Pending</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Points&#10;Processed</Data></Cell>
    <Cell><Data ss:Type="String">Team 1</Data></Cell>
    <Cell><Data ss:Type="String">Team 2</Data></Cell>
    <Cell><Data ss:Type="String">Team 3</Data></Cell>
   </Row>
<?php foreach($this->referees as $referee) { ?>
   <Row ss:Height="25.5">
    <Cell><Data ss:Type="String"><?php echo $referee->name1; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $referee->name2; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $referee->ageDesc; ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $referee->points->pending;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $referee->points->processed; ?></Data></Cell>

<?php foreach($referee->teams as $team) { ?>
    <Cell ss:StyleID="s21">
      <Data ss:Type="String"><?php echo  $team->desc . '&#10;' .
          'Pri: ' . $referee->getTeamPri($team) .
        ', Max: ' . $referee->getTeamMax($team) .
        ', Team ' . $referee->getTeamAct($team); ?>
      </Data>
    </Cell>
<?php }  ?>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>2</ActiveRow>
     <ActiveCol>7</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
