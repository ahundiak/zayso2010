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
  <Created>2009-10-26T14:41:19Z</Created>
  <Company>Intergraph Solutions Group</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>6150</WindowHeight>
  <WindowWidth>21915</WindowWidth>
  <WindowTopX>240</WindowTopX>
  <WindowTopY>60</WindowTopY>
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
  <Style ss:ID="s23" ss:Name="Hyperlink">
   <Font ss:Color="#0000FF" ss:Underline="Single"/>
  </Style>
  <Style ss:ID="s21">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Vertical="Bottom" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="s24" ss:Parent="s23">
   <Alignment ss:Vertical="Bottom" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="s26">
   <NumberFormat ss:Format="m/d/yy\ h:mm;@"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Sheet1">
  <Table 
    ss:ExpandedColumnCount="21" 
    ss:ExpandedRowCount="<?php echo count($this->referees)+2; ?>" 
    x:FullColumns="1"
    x:FullRows="1">
   <Column ss:Width="64.5"/>
   <Column ss:StyleID="s21" ss:AutoFitWidth="0"/>
   <Column ss:Width="53.25"/>
   <Column ss:Width="52.5"/>
   <Column ss:Width="50.0"/>
   <Column ss:Width="50.0"/>   
   <Column ss:AutoFitWidth="0" ss:Width="57.75"/>
   <Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="75"/>
   <Column ss:AutoFitWidth="0" ss:Width="50.0"/>
   <Column ss:Width="29.25"/>
   <Column ss:Width="30"/>
   <Column ss:AutoFitWidth="0" ss:Width="84"/>
   <Row>
    <Cell><Data ss:Type="String">Date Modified</Data></Cell>
    <Cell><Data ss:Type="String">Region</Data></Cell>
    <Cell><Data ss:Type="String">First Name</Data></Cell>
    <Cell><Data ss:Type="String">Last Name</Data></Cell>
    <Cell><Data ss:Type="String">AYSOID</Data></Cell>
    <Cell><Data ss:Type="String">Season</Data></Cell>
    <Cell><Data ss:Type="String">Age</Data></Cell>
    <Cell><Data ss:Type="String">Badge</Data></Cell>
    <Cell><Data ss:Type="String">Haven</Data></Cell>
    <Cell><Data ss:Type="String">Phones</Data></Cell>
    <Cell><Data ss:Type="String">Emails</Data></Cell>
    <Cell><Data ss:Type="String">CR</Data></Cell>
    <Cell><Data ss:Type="String">AR</Data></Cell>
    <Cell><Data ss:Type="String">Teams</Data></Cell>
    <Cell><Data ss:Type="String">Notes</Data></Cell>
    <Cell><Data ss:Type="String">Sat 7th</Data></Cell>
    <Cell><Data ss:Type="String">Sun 8th</Data></Cell>
    <Cell><Data ss:Type="String">Sat 14</Data></Cell>
    <Cell><Data ss:Type="String">Sun 15</Data></Cell>
    <Cell><Data ss:Type="String">Sat 21</Data></Cell>
    <Cell><Data ss:Type="String">Sun 22</Data></Cell>
   </Row>
   <Row ss:Height="25.5">
    <Cell ss:StyleID="s26"><Data ss:Type="DateTime">2009-10-26T00:00:00.000</Data></Cell>
    <Cell><Data ss:Type="String">R0894</Data></Cell>
    <Cell><Data ss:Type="String">Art</Data></Cell>
    <Cell><Data ss:Type="String">Hundiak</Data></Cell>
    <Cell><Data ss:Type="String">A123456</Data></Cell>
    <Cell><Data ss:Type="String">FS2009</Data></Cell>
    <Cell><Data ss:Type="String">Adult</Data></Cell>
    <Cell><Data ss:Type="String">Advanced</Data></Cell>
    <Cell><Data ss:Type="String">Ref 2007</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">C: 1234&#10;W: 5678</Data></Cell>
    <Cell ss:StyleID="s24" ss:HRef="mailto:email@hereemail@there"><Data
      ss:Type="String">email@here&#10;email@there</Data></Cell>
    <Cell><Data ss:Type="String">U14C</Data></Cell>
    <Cell><Data ss:Type="String">U19G</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Team 1&#10;Team 2</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Note 1&#10;Note 2</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
    <Cell><Data ss:Type="String">Avail</Data></Cell>
   </Row>
<?php 
  $item = new RefAvailDisplayItem($this->context);
  foreach($this->referees as $itemx) 
  {
    $item->setItem($itemx);
?>
   <Row ss:Height="25.5">
    <Cell ss:StyleID="s26"><Data ss:Type="DateTime"><?php echo $item->modified; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->region;?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->fname; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->lname; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->aysoid;?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->season;?></Data></Cell>    
    <Cell><Data ss:Type="String"><?php echo $item->age;   ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->badge; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->haven; ?></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"><?php echo $item->phones; ?></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"><?php echo $item->emails; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->divCR; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->divAR; ?></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"><?php echo $item->teams; ?></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"><?php echo $item->notes; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day1; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day2; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day3; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day4; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day5; ?></Data></Cell>
    <Cell><Data ss:Type="String"><?php echo $item->day6; ?></Data></Cell>
   </Row>
<?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
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
