<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Art Hundiak</Author>
  <LastAuthor>Art Hundiak</LastAuthor>
  <Created>2009-10-23T20:03:32Z</Created>
  <Company>Intergraph Solutions Group</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>15330</WindowHeight>
  <WindowWidth>19995</WindowWidth>
  <WindowTopX>480</WindowTopX>
  <WindowTopY>45</WindowTopY>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="Medium Time"/>
  </Style>
  <Style ss:ID="s39">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="[ENG][$-409]ddd\ mmm\ dd;@"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Sheet1">
  <Table 
    ss:ExpandedColumnCount="8" 
    ss:ExpandedRowCount="<?php echo count($this->events) + 2; ?>" 
    x:FullColumns="1"
    x:FullRows="1">
   <Column ss:StyleID="s21" ss:AutoFitWidth="0"/>
   <Column ss:StyleID="s21" ss:Width="52.5"/>
   <Column ss:Width="30"/>
   <Column ss:Width="52.5"/>
   <Column ss:StyleID="s21" ss:AutoFitWidth="0"/>
   <Column ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="213.75" ss:Span="1"/>
   <Row>
    <Cell><Data ss:Type="String">Event ID</Data></Cell>
    <Cell><Data ss:Type="String">Event Num</Data></Cell>
    <Cell><Data ss:Type="String">Div</Data></Cell>
    <Cell><Data ss:Type="String">Date</Data></Cell>
    <Cell><Data ss:Type="String">Time</Data></Cell>
    <Cell><Data ss:Type="String">Field</Data></Cell>
    <Cell><Data ss:Type="String">Home Team</Data></Cell>
    <Cell><Data ss:Type="String">Away Team</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="Number">1234</Data></Cell>
    <Cell><Data ss:Type="Number">56</Data></Cell>
    <Cell><Data ss:Type="String">U14G</Data></Cell>
    <Cell ss:StyleID="s39"><Data ss:Type="DateTime">2009-11-07T00:00:00.000</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="DateTime">1899-12-31T13:15:00.000</Data></Cell>
    <Cell><Data ss:Type="String">Palmer Int</Data></Cell>
    <Cell><Data ss:Type="String">Home A1</Data></Cell>
    <Cell><Data ss:Type="String">Away A2</Data></Cell>
   </Row>
<?php 
    foreach($this->events as $event) 
    { 
    	
?>
   <Row>
    <Cell><Data ss:Type="Number"><?php echo $event->id; ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $event->num;?></Data></Cell>
    <Cell><Data ss:Type="String">U14G</Data></Cell>
    <Cell ss:StyleID="s39"><Data ss:Type="DateTime">2009-11-07T00:00:00.000</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="DateTime">1899-12-31T13:15:00.000</Data></Cell>
    <Cell><Data ss:Type="String">Palmer Int</Data></Cell>
    <Cell><Data ss:Type="String">Home A1</Data></Cell>
    <Cell><Data ss:Type="String">Away A2</Data></Cell>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
     <ActiveCol>4</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Sheet2">
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Sheet3">
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
