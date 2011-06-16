<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>

  <meta http-equiv="Content-Type"     content="text/html; charset=utf-8" >
  <meta http-equiv="Content-Language" content="en" >

  <title><?php echo $title; ?></title>

  <style type="text/css">

/* ============================================
 * Your basic reset
 */
html, body, div, span,
h1, h2, h3, h4, h5, h6,
p, a, img,
ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td
{
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
}
img
{ 
  display: block;
  border:  none;
}

body {
  background-color: LightGreen;
}
#layout-header-table
{
  display:    table;
  width:      100%;
  background: red;
}
#layout-header-row
{
  display: table-row;
}
.layout-header-logo-cell
{
  display: table-cell;
}
#layout-header-center-cell
{
  display: table-cell;
  background-color: LightBlue;

  width:   100%;  /* To expand the center node */
  
  vertical-align: top; /* Key to getting the content pushed up */

  position: relative; /* To allow positioning text along bottom of division */
  
  margin:       0;
  padding-left: 5px;
  padding-top:  3px;
}
#layout-header-center-cell-top
{
  font-size: 1.2em;  /* Make text a bit bigger */
}
#layout-header-center-cell-bottom
{
  position: absolute;  /* Position along bottom of cell */
  bottom:   0px;
}
</style>

</head>
<body>
  <div id="layout-document">
    <div id="layout-header-table">
      <div id="layout-header-row">
        <p class="layout-header-logo-cell" >
          <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50" alt="Logo Left"  >
        </p>
        <div id="layout-header-center-cell">
          <p id="layout-header-center-cell-top"   >Header Line Along the Top</p>
          <p id="layout-header-center-cell-bottom">Header Line Along the Bottom</p>
        </div>
        <p class="layout-header-logo-cell">
          <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50" alt="Logo Right" >
        </p>
      </div>
    </div>
    <div id="layout-content">
      <h1>Some content</h1>
      <?php // echo $content; ?>
    </div>
  </div>
</body>
</html>
<?php /* ?>
          <div id="layout-header-center-cell">
          <div style="display: table;">
            <div style="display: table-row">
              <p style="display: table-cell;" id="layout-header-center-cell-topx"   >Header Line Along the Top</p>
            </div>
            <div style="display: table-row">
              <p style="display: table-cell; vertical-align: bottom;" id="layout-header-center-cell-bottomx">Header Line Along the Bottom</p>
            </div>
          </div>
        </div>
<?php */ ?>