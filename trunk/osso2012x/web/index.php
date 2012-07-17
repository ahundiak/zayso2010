<?php 
    /* 
     * localhost on solaris 10
     * zayso.org on zayso server
     */
    
    $hostName = $_SERVER['HTTP_HOST'];
    
    $host = sprintf('http://%s/',$hostName); 
?>
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
        <a href="<?php echo $host; ?>area5c">AYSO Area 5C/F, North Alabama, Fall 2012</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>arbiter/tourn/kicks">USSF HFC Kicks Invitational 2012, Huntsville Alabama, October 19-21</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>s5games">AYSO Section 5 Games 2013, TBD</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>natgames">AYSO National Games 2012, Knoxville, TN, July 4-8 2012 (Legacy)</a>
      </li>
      <!-- 
      <li>
        <a href="<?php echo $host; ?>arbiter/tourn/opencup">USSF Open Cup 2012, Decatur Alabama, April 13-15</a>
      </li>
       <li>
        <a href="<?php echo $host; ?>arbiter/tourn/classic">USSF HFC Spring Classic 2012, Huntsville Alabama, April 20-22</a>
      </li>
      <li>
        <a href="<?php echo $host; ?>arbiter/tourn/statecup">USSF AL State Cup 2012, Decatur Alabama, May 4-6</a>
      </li>
       <li>
        <a href="<?php echo $host; ?>arbiter/tourn/statecupff">USSF AL State Cup Final Four 2012, Decatur Alabama, May 11-13</a>
      </li>
      -->
    </ul>
    </body>
</html>
