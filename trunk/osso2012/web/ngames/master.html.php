<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="reset.css">
  <link rel="stylesheet" type="text/css" href="natgames.css">
</head>
<body>
<header id="banner">
  <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50"/>
  <img src="NatlGames_Logo_2012_sm.jpg" height="50" width="50" align="right"/>

  <div 
    style="color: red; background-color: LightBlue; position: absolute; left: 60px; top: 5px;"
  >THE Header<br />Header Line 2</div>

</header>

<nav id="menu-top">
  <h3>The Top Menu</h3>
</nav>

<div id="content">
<?php echo $content; ?>
</div>
</body>
</html>
