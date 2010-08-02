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
/* Top menu bar navigation */
div#layout-menu-top
{
  color:      black;
  background: <?php echo $bgcMenuTop; ?>;
  height:     25px;

  border-style: none; /* None */
  border-width: medium;
  border-color: red;

  clear: both;

}
div#layout-menu-top ul
{
  list-style-type: none;
  margin:  0px;
  padding: 2px 2px 2px 2px; /* 5 */
}
div#layout-menu-top ul li
{
  display: inline;
}
div#layout-menu-top ul li a
{
  padding: 0px   5px 0px 5px; /* 20 */ /* Top right bottom left */
  margin:  2px   2px 2px 2px;

  border:  1px solid #666;

  background-color: #CCC;  /* Grayish */
  color:            black;
  text-decoration:  none;
  text-align:       center;

  font-size    : .70em;
}
div#layout-menu-top ul li a:hover
{
  background-color: #333;
  color:            #FFF;
}
/* =========================================================== */
/* Content stuff */
div#layout-content
{
  background-color: <?php echo $bgcContent; ?>;
  color: black;

  padding-top:      1px;
  padding-left:     3px;
  padding-bottom:   3px;

  border-style: none;
  border-width: medium;
  border-color: blue;

}
h1 {color:green;background:white}
h2 {color:blue; background:white}

h3
{
  color:            green;
  background-color: #FFFFCC; /* Tan */
  margin:           0px;
}
h4
{
  color:            black;
  background-color: white;
  margin:           2px;
}
p
{
  margin:           2px;
}

fieldset.entry2
{
  font-size:        12px;
/*  color:            black; */
/*  background-color: #CCCCCC; */
  padding:          0px;
  width:            35em;
}
fieldset.entry2 legend
{
}
fieldset.entry2 label
{
  width:      10em;
  float:      left;
  clear:      both;
  text-align: right;
  margin:     .5em 1em;
}
fieldset.entry2 input, textarea
{
  width:      15em;
  float:      left;
  text-align: right;
  margin:    .5em 0em;
}
fieldset.entry2 input.submit
{
  width:      auto;
  float:      right;
  clear:      both;
  margin-bottom:  1em;
  margin-left:    7em;
  text-align:     center;
}
fieldset.entry2 br
{
  clear: both;
}

table.entry2
{
  width:        400px;
  border-style: solid;
  border-width: 1px;
  border-color: black;
  padding:      2px;
  margin-left:  5px;
}
table.entry2 td.data
{
  border-style: solid;
  border-width: 1px;
  border-color: black;

  padding:     2px;
  margin-left: 4px;
}
table.entry2 td.label
{
  border-style: solid;
  border-width: 1px;
  border-color: black;

  padding:      2px;
  margin-right: 6px;

  text-align: center;
}
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
