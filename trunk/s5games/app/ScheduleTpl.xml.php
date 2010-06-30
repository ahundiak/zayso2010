<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Dianne</Author>
  <LastAuthor>ahundiak</LastAuthor>
  <Created>2009-06-10T15:30:21Z</Created>
  <LastSaved>2009-06-11T12:50:44Z</LastSaved>
  <Company>SMOKEY MOUNTAIN SERVICES</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8736</WindowHeight>
  <WindowWidth>11472</WindowWidth>
  <WindowTopX>360</WindowTopX>
  <WindowTopY>132</WindowTopY>
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
   <Interior/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Interior/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Interior/>
   <NumberFormat ss:Format="0"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Interior/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Interior/>
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="s26">
   <Borders/>
   <Interior/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Interior/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Interior/>
   <NumberFormat ss:Format="0"/>
  </Style>
  <Style ss:ID="s29">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Borders/>
   <Interior/>
  </Style>
  <Style ss:ID="s30">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Interior/>
   <NumberFormat ss:Format="@"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Schedule">
  <Table 
  	ss:ExpandedColumnCount="13" 
  	ss:ExpandedRowCount="<?php echo $tpl->gameCnt + 1; ?>" 
  	x:FullColumns="1"
   	x:FullRows="1" 
   	ss:StyleID="s21" 
   	ss:DefaultRowHeight="13.2">
   <Column ss:StyleID="s21" ss:Width="21.6"/>
   <Column ss:StyleID="s22" ss:Width="31.2"/>
   <Column ss:StyleID="s21" ss:Width="24.6"/>
   <Column ss:StyleID="s22" ss:Width="33.6"/>
   <Column ss:StyleID="s25" ss:Width="28.2"/>
   <Column ss:StyleID="s23" ss:Width="60.0"/>
   <Column ss:StyleID="s22" ss:Width="100.0"/>
   <Column ss:StyleID="s22" ss:Width="100.0"/>
   <Column ss:StyleID="s22" ss:Width="110.0"/>
   <Column ss:StyleID="s22" ss:Width="110.0"/>
   <Column ss:StyleID="s22" ss:Width="110.0"/>
   <Column ss:StyleID="s22" ss:Width="90.0"/>
   <Column ss:StyleID="s22" ss:Width="10.0"/>
    <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s22"><Data ss:Type="String">G #</Data></Cell>
    <Cell><Data ss:Type="String">DATE</Data></Cell>
    <Cell><Data ss:Type="String">TIME</Data></Cell>
    <Cell><Data ss:Type="String">FIELD</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">DIV</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">BRACKET</Data></Cell>
    <Cell><Data ss:Type="String">HOME</Data></Cell>
    <Cell><Data ss:Type="String">AWAY</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CR</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">AR1</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">AR2</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">Assessor</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
   </Row>
   <?php foreach($tpl->games as $game) { ?>
   <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s26"><Data ss:Type="Number"><?php echo $game->id;       ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $game->date;     ?></Data></Cell>
    <Cell ss:StyleID="s30"><Data ss:Type="String"><?php echo $game->time;     ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $game->field;    ?></Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String"><?php echo $game->div;      ?></Data></Cell>
    <Cell ss:StyleID="s28"><Data ss:Type="String"><?php echo $game->bracket;  ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $game->homeName; ?></Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String"><?php echo $game->awayName; ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><?php echo $this->displayPerson($game,1); ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><?php echo $this->displayPerson($game,2); ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><?php echo $this->displayPerson($game,3); ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><?php echo $this->displayPerson($game,4); ?></Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><?php echo $this->displayPerson($game,5); ?></Data></Cell>
   </Row>
   <?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
   </PageSetup>
   <Unsynced/>
   <FitToPage/>
   <Print>
    <ValidPrinterInfo/>
    <Scale>90</Scale>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
    <Gridlines/>
   </Print>
   <Selected/>
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
  <Sorting xmlns="urn:schemas-microsoft-com:office:excel">
   <Sort>G #</Sort>
  </Sorting>
 </Worksheet>
</Workbook>
