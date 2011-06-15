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
  
  margin:  0;
  padding: 0;
  border:  0;
}

#layout-header-row
{
  display: table-row;
  
  margin:  0;
  padding: 0;
  border:  0;
}
#layout-header-center-cell
{
  display: table-cell;
  width:   100%;  /* To expand the center node */

  background-color: LightBlue;
  
  vertical-align: top;

  
  margin:  0;
  padding: 0;
  border:  0;
}
#layout-header-logo-cell
{
  display: table-cell;

  margin:  0;
  padding: 0;
  border:  0;
}

</style>

</head>
<body>
  <div id="layout-document">
    <div id="layout-header-table">
      <div id="layout-header-row">
        <p id="layout-header-logo-cell" >
          <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50" alt="Logo Left"  />
        </p>
        <div id="layout-header-center-cell">
          <p>Header Line 1</p>
          <p>Header Line 1</p>
        </div>
        <p id="layout-header-logo-cell">
          <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50" alt="Logo Right" />
        </p>
      </div>
    </div>

    <div id="layout-content">
      <h1>Some content</h1>
      <?php //echo $content; ?>
    </div>
  </div>
</body>
</html>
