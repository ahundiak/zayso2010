<?php echo "<?xml version=\"1.0\"?>\n" ?>
<?php echo "<?mso-application progid=\"Excel.Sheet\"?>\n" ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <LastAuthor>ahundiak</LastAuthor>
  <Created>2006-08-02T18:51:45Z</Created>
  <LastSaved>2006-08-06T00:25:00Z</LastSaved>
  <Version>11.8107</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <Colors>
   <Color>
    <Index>39</Index>
    <RGB>#E3E3E3</RGB>
   </Color>
  </Colors>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>5496</WindowHeight>
  <WindowWidth>9660</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>0</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom" ss:Horizontal="Center"/>
   <Borders/>
   <Font/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s16">
   <Font ss:Bold="1"/>
   <Alignment ss:Horizontal="Center"/>
  </Style>
  <Style ss:ID="s19">
   <Font ss:Bold="1"/>
   <NumberFormat ss:Format="[ENG][$-409]dd\-mmm\-yy;@"/>
  </Style>
  <Style ss:ID="s20">
   <NumberFormat ss:Format="[ENG][$-409]dd\-mmm\-yy;@"/>
  </Style>
  <Style ss:ID="s22">
   <Font ss:Bold="1"/>
   <NumberFormat ss:Format="[$-409]h:mm\ AM/PM;@"/>
  </Style>
  <Style ss:ID="s23">
   <NumberFormat ss:Format="[$-409]h:mm\ AM/PM;@"/>
  </Style>
 </Styles>
<?php 
    foreach($this->eventDivs as $eventDivDesc => $eventDiv) { 
        if (count($eventDiv)) {
 ?>
 <Worksheet ss:Name="<?php echo $eventDivDesc; ?>">
  <Table 
    ss:ExpandedColumnCount="12" 
    ss:ExpandedRowCount="<?php echo count($eventDiv) + 1; ?>" 
    x:FullColumns="1"
    x:FullRows="1" 
    ss:DefaultRowHeight="13.2">
   <Column ss:AutoFitWidth="0" ss:Width="46.800000000000004"/>
   <Column ss:AutoFitWidth="0" ss:Width="36"/>
   <Column ss:StyleID="s20" ss:Width="51"/>
   <Column ss:AutoFitWidth="0" ss:Width="50.4"/>
   <Column ss:StyleID="s23" ss:AutoFitWidth="0" ss:Width="50.4"/>
   <Column ss:AutoFitWidth="0" ss:Width="99.0"/>
   <Column ss:AutoFitWidth="0" ss:Width="72.6"/>
   <Column ss:AutoFitWidth="0" ss:Width="77.399999999999991"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.0"/>
   <Column ss:AutoFitWidth="0" ss:Width="77.399999999999991"/>
   <Column ss:AutoFitWidth="0" ss:Width="99.0"/>
   <Column ss:AutoFitWidth="0" ss:Width="72.6"/>
   <Row>
    <Cell ss:StyleID="s16"><Data ss:Type="String">game_id</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Div</Data></Cell>
    <Cell ss:StyleID="s19"><Data ss:Type="String">Date</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Day</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Time</Data></Cell>
    
    <Cell ss:StyleID="s16"><Data ss:Type="String">Away Coach</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Away Phone</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Away Team </Data></Cell>

    <Cell ss:StyleID="s16"><Data ss:Type="String">Field</Data></Cell>
    
    <Cell ss:StyleID="s16"><Data ss:Type="String">Home Team</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Home Coach</Data></Cell>
    <Cell ss:StyleID="s16"><Data ss:Type="String">Home Phone</Data></Cell>
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
    <Cell><Data ss:Type="Number"  ><?php echo $event->id; ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $event->divisionDesc; ?></Data></Cell>
    <Cell><Data ss:Type="DateTime"><?php echo $this->formatDateIso($event->date); ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $this->formatDateDow($event->date); ?></Data></Cell>
    <Cell><Data ss:Type="DateTime"><?php echo $this->formatTimeIso($event->time); ?></Data></Cell>
    
    <Cell><Data ss:Type="String"  ><?php echo $teamAway->coachHead->name; ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $teamAway->coachHead->phoneHome->number; ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $teamAway->key; ?></Data></Cell>

    <Cell><Data ss:Type="String"  ><?php echo $event->fieldDesc; ?></Data></Cell>
    
    <Cell><Data ss:Type="String"  ><?php echo $teamHome->key; ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $teamHome->coachHead->name; ?></Data></Cell>
    <Cell><Data ss:Type="String"  ><?php echo $teamHome->coachHead->phoneHome->number; ?></Data></Cell>
   </Row>
<?php }} ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Print>
    <FitWidth>0</FitWidth>
    <FitHeight>0</FitHeight>
    <ValidPrinterInfo/>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
    <Gridlines/>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>12</ActiveRow>
     <ActiveCol>10</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
<?php }} ?>
</Workbook>
