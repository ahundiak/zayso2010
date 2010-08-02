<?php
$colorPaleGreen   = '#CCFFCC';
$colorBrightGreen = '#99FF33';
$colorPaleYellow  = '#FFFFCC';

$colorWhite = 'white';
$colorRed   = 'red';
$colorBlue  = 'blue';

$colorHeader = $colorPaleGreen;

$bgcBody   = $colorWhite; // Pale Green
$bgcPage   = $colorHeader; //'white'; TODOx On IE8 getting Extra pahe space
$bgcContent= $colorPaleYellow;

$bgcBanner      = $colorHeader; // Bright Green
$bgcBannerLeft  = $bgcBanner;
$bgcBannerRight = $bgcBanner;

$bgcMenuTop = $colorHeader;
?>
body#layout-body
{
  color:      black;
  background: <?php echo $bgcBody; ?>;

  margin:  0;  /* With respect to containing element */
  padding: 0;  /* Around internal elements */

  font-size: 100%;

}
div#layout-page
{
  padding:      0px;

  color:      black;
  background: <?php echo $bgcPage; ?>;

  border-style: none; /*groove;*/
  border-width: medium;
  border-color: black;
}
div#layout-banner
{
  color:      black;
  background: <?php echo $bgcBanner; ?>;

  border-style: none; /* None */
  border-width: medium;
  border-color: green;

  height: 40px;
}
div#layout-banner-left
{
  color:      black;
  background: <?php echo $bgcBannerLeft; ?>;

  /* margin-right: 200px; */

  float: left;
  width: 525px;
}
div#layout-banner-left span
{
  margin-left: 3px; /* 3px */
    font-size    : .90em;
}
div#layout-banner-left span.bold
{
  font-family  :  "Times New Roman", Times, serif;
  font-size    : 1.90em;
}

div#layout-banner-right
{
  color:      black;
  background: <?php echo $bgcBannerRight; ?>;

  float: right;
  width: 125px;

  text-align: right;
}
div#layout-banner-right span
{
  margin-right: 0px; /* 3px */
  font-size    : .75em;

}
/* ================================================= */
/* Standard data entry table */
table.entry
{
  border-style: solid;
  border-width: 2px;
  border-color: black;
  padding:      2px;
  margin-left:  5px;
}
table.entry td
{
  border-style: solid;
  border-width: 1px;
  border-color: black;

  padding:     2px;
  margin-left: 4px;
}
table.entry th
{
  border-bottom-style: solid;
  border-width: 2px;
  border-color: black;

  padding:     2px;
  margin-left: 4px;
}
