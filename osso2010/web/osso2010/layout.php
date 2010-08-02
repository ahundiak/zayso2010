<html>
<head>
  <title>Layout Test</title>
  <style type="text/css">
body#layout-body
{
  color:      black;
  background: #CCFFCC; /* Pale Green */

  margin:  0;  /* With respect to containing element */
  padding: 0;  /* Around internal elements */

  font-size: 100%;

}
div#layout-page
{
  /*
  position: absolute;
  left:         50%;
  width:        900px;
  margin-left: -450px; */
  /*width:        900px;*/

  padding:      0px;

  color:      black;
  background: red;
/*
  border-style: groove;
  border-width: medium;
  border-color: black;*/
}
div#layout-banner
{
  color:      black;
  background: lightgreen;
  height:     40px; /* Need this to keep things aligned */
}
div#layout-banner-left
{
  color:        white;
  background:   red;
/*margin-right: 25%; */
  float:        left;
  width:        400px; /* 75.0%; */
}
div#layout-banner-right
{
  color:      black;
  background: blue;
  float:      right;

  width:      200px; /* 25.0%; */
  text-align: right;

  margin-right: -1px; /* Stops the drop due to rounding errors in IE */
  padding-right: 0px;
}
div#layout-banner-right span
{
  padding-right: 10px;
  padding-left:  10px;
  margin-right: 3px;
  background: lightcyan;
}
div#layout-menu-top
{
  color:      black;
  background: lightblue;
  clear:      both;
  height:     50px;
}

  </style>
</head>
<body id="layout-body">
  <div id="layout-page">
    <div id="layout-banner">
      <div id="layout-banner-left" >
        <div>
        Left and put in some coentnet to force things to wrap aoround and see what happens as the width gets less
        </div>
      </div>
      <div id="layout-banner-right">
        <span>Span 1</span>
        <span>Span 2</span>
        <span>Span 1</span>
        <span>Span 2</span>
        <span>Span 1</span>
        <span>Span 2</span>
        <span>Span 1</span>
        <span>Span 2</span>
        <span style="margin-right: 10px;">Span 3</span>
      </div>
  </div>
    <div id="layout-menu-top">
      The Top Menu
    </div>
  </div>
</body>
</html>

