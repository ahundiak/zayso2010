<?php $host = 'http://localhost/'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Zayso Portal</title>
    <meta name="description" content="Zayso Referee Scheduling"/>
    <meta name="author"      content="Art Hundiak"/>
    <style>
        ul { margin: 5px; padding: 5px;}
        li { margin: 5px; padding: 5px;}
    </style>    
</head>
<body>
    <h1>Welcome to the Zayso Portal</h1>
    <p>Please click on one of the following AYSO/USSF game/referee scheduling sites:</p>
    <ul>
      <li>
        <a href="<?php echo $host; ?>natgames">AYSO National Games 2012, Knoxville, TN, July 4-8 2012</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>area5c">AYSO Area 5C/F, North Alabama</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>s5games">AYSO Section 5 Games 2012, Charlotte, NC, June 15-17 2012</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>arbiter/tourn/opencup">USSF Open Cup 2012, Decatur Alabama, April 13-15</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>arbiter/tourn/classic">USSF HFC Spring Classic 2012, Huntsville Alabama, April 20-22</a>
      </li>
    </ul>
    </body>
</html>
