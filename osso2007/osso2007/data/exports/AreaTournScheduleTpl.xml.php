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
  <Created>2009-11-04T14:55:39Z</Created>
  <Company>Intergraph Solutions Group</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>15330</WindowHeight>
  <WindowWidth>23835</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>45</WindowTopY>
  <ActiveSheet>0</ActiveSheet>
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
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="ddd\ mmm\ dd"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="hh:mm\ AM/PM"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Schedule">
  <Table 
  ss:ExpandedColumnCount="15" 
  ss:ExpandedRowCount="<?php echo count($events) + 1; ?>" 
  x:FullColumns="1"
  x:FullRows="1">
   <Column ss:StyleID="s21" ss:AutoFitWidth="0" ss:Width="35.25" ss:Span="1"/>
   <Column ss:Index="3" ss:StyleID="s21" ss:AutoFitWidth="0" ss:Width="56.25"
    ss:Span="1"/>
   <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="82.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="30" ss:Span="1"/>
   <Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="98.25" ss:Span="7"/>
   <Row>
    <Cell><Data ss:Type="String">ID</Data></Cell>
    <Cell><Data ss:Type="String">NUM</Data></Cell>
    <Cell><Data ss:Type="String">DATE</Data></Cell>
    <Cell><Data ss:Type="String">TIME</Data></Cell>
    <Cell><Data ss:Type="String">FIELD</Data></Cell>
    <Cell><Data ss:Type="String">DIV</Data></Cell>
    <Cell><Data ss:Type="String">BRAC</Data></Cell>
    <Cell><Data ss:Type="String">HOME</Data></Cell>
    <Cell><Data ss:Type="String">AWAY</Data></Cell>
    <Cell><Data ss:Type="String">CENTER</Data></Cell>
    <Cell><Data ss:Type="String">AR1</Data></Cell>
    <Cell><Data ss:Type="String">AR2</Data></Cell>
    <Cell><Data ss:Type="String">4th OFFICIAL</Data></Cell>
    <Cell><Data ss:Type="String">OBSERVER</Data></Cell>
    <Cell><Data ss:Type="String">STAND BY</Data></Cell>
   </Row>
<?php foreach($events as $event) { ?>
   <Row>
    <Cell><Data ss:Type="Number"><?php echo $event->id;  ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $event->num; ?></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="DateTime"><?php echo $event->datex; ?></Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="DateTime"><?php echo $event->timex; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->field;    ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->div;      ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->brac;     ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->homeTeam; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->awayTeam; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->cr;       ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->ar1;      ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->ar2;      ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->fourth;   ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->observer; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $event->standby;  ?></Data></Cell>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
   </PageSetup>
   <FitToPage/>
   <Print>
    <FitHeight>2</FitHeight>
    <ValidPrinterInfo/>
    <Scale>58</Scale>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
    <Gridlines/>
   </Print>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
     <ActiveCol>10</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Referees">
  <Table 
  ss:ExpandedColumnCount="22" 
  ss:ExpandedRowCount="<?php echo count($referees) + 2; ?>" 
  x:FullColumns="1"
  x:FullRows="1">
   <Column ss:StyleID="s21" ss:AutoFitWidth="0" ss:Width="30"/>
   <Column ss:AutoFitWidth="0" ss:Width="45.75" ss:Span="1"/>
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="82.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="108.75" ss:Span="1"/>
   <Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="82.5" ss:Span="4"/>
   <Column ss:Index="12" ss:StyleID="s24" ss:AutoFitWidth="0" ss:Width="30"
    ss:Span="5"/>
   <Column ss:Index="18" ss:AutoFitWidth="0" ss:Width="30" ss:Span="3"/>
   <Column ss:Index="22" ss:StyleID="s24" ss:AutoFitWidth="0" ss:Width="35.25"/>
   <Row>
    <Cell ss:Index="12" ss:MergeAcross="1"><Data ss:Type="String">U10</Data></Cell>
    <Cell ss:MergeAcross="1"><Data ss:Type="String">U12</Data></Cell>
    <Cell ss:MergeAcross="1"><Data ss:Type="String">U14</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s24"><Data ss:Type="String">U16</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s24"><Data ss:Type="String">U19</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">ID</Data></Cell>
    <Cell><Data ss:Type="String">REGION</Data></Cell>
    <Cell><Data ss:Type="String">AGE</Data></Cell>
    <Cell><Data ss:Type="String">BADGE</Data></Cell>
    <Cell><Data ss:Type="String">First Name</Data></Cell>
    <Cell><Data ss:Type="String">Last Name</Data></Cell>
    <Cell><Data ss:Type="String">Phone Home</Data></Cell>
    <Cell><Data ss:Type="String">Phone Work</Data></Cell>
    <Cell><Data ss:Type="String">Phone Cell</Data></Cell>
    <Cell><Data ss:Type="String">Email Home</Data></Cell>
    <Cell><Data ss:Type="String">Email Work</Data></Cell>
    <Cell><Data ss:Type="String">CR</Data></Cell>
    <Cell><Data ss:Type="String">AR</Data></Cell>
    <Cell><Data ss:Type="String">CR</Data></Cell>
    <Cell><Data ss:Type="String">AR</Data></Cell>
    <Cell><Data ss:Type="String">CR</Data></Cell>
    <Cell><Data ss:Type="String">AR</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CR</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">AR</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CR</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">AR</Data></Cell>
    <Cell><Data ss:Type="String">All</Data></Cell>
   </Row>
<?php foreach($referees as $ref) { ?>
   <Row>
    <Cell><Data ss:Type="Number"><?php echo $ref->id;     ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->region; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->age;    ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->badge;  ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->fname;  ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->lname;  ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->phoneHome; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->phoneWork; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->phoneCell; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->emailHome; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $ref->emailWork; ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->cr10;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->ar10;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->cr12;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->ar12;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->cr14;   ?></Data></Cell>
    <Cell><Data ss:Type="Number"><?php echo $ref->ar14;   ?></Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number"><?php echo $ref->cr16; ?></Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number"><?php echo $ref->ar16; ?></Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number"><?php echo $ref->cr19; ?></Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number"><?php echo $ref->ar19; ?></Data></Cell>
    <Cell ss:Formula="=SUM(RC[-10]:RC[-1])"><Data ss:Type="Number">55</Data></Cell>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <FitToPage/>
   <Print>
    <FitHeight>10</FitHeight>
    <ValidPrinterInfo/>
    <Scale>40</Scale>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
    <Gridlines/>
   </Print>
   <Selected/>
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
