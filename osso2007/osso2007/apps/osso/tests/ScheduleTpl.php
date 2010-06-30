<?php echo "<?xml version=\"1.0\"?>\n" ?>
<?php echo "<?mso-application progid=\"Excel.Sheet\"?>\n" ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>ahundiak</Author>
  <LastAuthor>ahundiak</LastAuthor>
  <Created>2007-07-20T15:27:11Z</Created>
  <Company>SG&amp;I</Company>
  <Version>11.8107</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>11076</WindowHeight>
  <WindowWidth>18732</WindowWidth>
  <WindowTopX>384</WindowTopX>
  <WindowTopY>312</WindowTopY>
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
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s28">
   <NumberFormat ss:Format="[$-409]mm/dd/yy\ hh:mm\ AM/PM;@"/>
  </Style>
  <Style ss:ID="s29">
   <NumberFormat ss:Format="[ENG][$-409]ddd\ mmm\ dd\ hh:mm\ AM/PM;@"/>
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s30">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="00"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Teams">
  <Table 
    ss:ExpandedColumnCount="9" 
    ss:ExpandedRowCount="<?php echo count($this->teams) + 1; ?>" 
    x:FullColumns="1"
    x:FullRows="1" ss:DefaultRowHeight="13.2">
   <Column ss:Width="27.0"/>
   <Column ss:Width="27.0"/>
   <Column ss:Width="40.0"/>
   <Column ss:Width="38.4"/>
   <Column ss:Width="42.6"/>
   <Column ss:Width="27.0"/>
   <Column ss:Width="100.0"/>
   <Column ss:Width="80.0"/>
   <Column ss:Width="250.0"/>
   <Row>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Team</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Year</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Season</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Region</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Division</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Num</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Coach</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Home Phone</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">Team Key</Data></Cell>
   </Row>
   <?php foreach($this->teams as $team) { ?>
   <Row>
    <Cell ss:StyleID="s27"><Data ss:Type="Number"><?php echo $team->id; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="Number"><?php echo $team->year; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $team->seasonTypeDesc; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $team->unitKey; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $team->divisionDesc; ?></Data></Cell>
    <Cell ss:StyleID="s30"><Data ss:Type="Number"><?php echo $team->divisionSeqNum; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $team->coachHead->name; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $team->coachHead->phoneHome->number; ?></Data></Cell>
    <Cell ss:StyleID="s27"
     ss:Formula="=CONCATENATE(RC[-5],&quot;-&quot;,RC[-4],&quot;-&quot;,TEXT(RC[-3],&quot;00&quot;),&quot; &quot;,RC[-2],&quot; &quot;,RC[-1])">
     <Data ss:Type="String"></Data>
    </Cell>
   </Row>
  <?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Print>
    <ValidPrinterInfo/>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <?php 
    foreach($this->eventDivs as $eventDivDesc => $eventDiv) { 
        if (count($eventDiv)) {
 ?>
 <Worksheet ss:Name="<?php echo $eventDivDesc; ?>">
  <Table 
   ss:ExpandedColumnCount="5" 
   ss:ExpandedRowCount="<?php echo count($eventDiv) + 1; ?>" 
   x:FullColumns="1"
   x:FullRows="1" 
   ss:DefaultRowHeight="13.2">
   <Column ss:StyleID="s27" ss:Width="30.0"/>
   <Column ss:StyleID="s27" ss:Width="100.0"/>
   <Column ss:StyleID="s27" ss:Width="100.0"/>
   <Column ss:StyleID="s27" ss:Width="250.0"/>
   <Column ss:StyleID="s27" ss:Width="250.0"/>
   <Row>
    <Cell><Data ss:Type="String">Game</Data></Cell>
    <Cell><Data ss:Type="String">Date Time</Data></Cell>
    <Cell><Data ss:Type="String">Field</Data></Cell>
    <Cell><Data ss:Type="String">Home Team</Data></Cell>
    <Cell><Data ss:Type="String">Away Team</Data></Cell>
   </Row>
<?php
    $index = 1; // Title Row
    $wasBlank = FALSE;
    foreach($eventDiv as $event) {
        $index++;
        if ($event->isBlank()) {
            $wasBlank = TRUE;
        }
        else {
            $teamHome = $event->teamHome;
            $teamAway = $event->teamAway;
 ?>
   <Row <?php if ($wasBlank) { $wasBlank = FALSE; echo " ss:Index=\"{$index}\""; } ?>>
    <Cell><Data ss:Type="Number"><?php echo $event->id; ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="DateTime"><?php echo $this->formatDateTimeIso($event->date,$event->time); ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->fieldDesc; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $this->formatTeam($teamHome); ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $this->formatTeam($teamAway); ?></Data></Cell>
<!-- 
    <Cell ss:Formula="=VLOOKUP(<?php echo $teamHome->phyTeam->id; ?>,Teams!R[-1]:R[65534],9)"><Data ss:Type="String"></Data></Cell>
    <Cell ss:Formula="=VLOOKUP(<?php echo $teamAway->phyTeam->id; ?>,Teams!R[-1]:R[65534],9)"><Data ss:Type="String"></Data></Cell>
-->
   </Row>   
<?php }} ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>7</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <?php }} ?>
</Workbook>
