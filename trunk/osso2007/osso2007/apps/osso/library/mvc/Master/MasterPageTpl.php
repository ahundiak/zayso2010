<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">            
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php 
    if ($this->tplRedirectDelay && $this->tplRedirectLink) {
    echo "<meta http-equiv=\"refresh\" content=\"{$this->tplRedirectDelay};url={$this->tplRedirectLink}\" />";
    }
?>        
<title><?php echo $this->escape($this->tplTitle); ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/layout.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/osso.css');   ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/menu.css');   ?>" />

<?php /* 
<script type="text/javascript"><!--//--><![CDATA[//><!--

sfHover = function() {
    var sfEls = document.getElementById("nav").getElementsByTagName("LI");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
            this.className+=" sfhover";
        }
        sfEls[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
        }
    }
}
if (window.attachEvent) window.attachEvent("onload", sfHover);

//--><!]]></script>
*/ ?>
</head>
<body id="layout-body"><div id="layout-page">

<div  id="layout-banner">

<?php $user = $this->context->user; ?>

<div  id="layout-banner-left">

<span>Zayso</span>

<span>- Season: 
  <?php echo $this->escape($user->seasonTypeDesc); ?>
  <?php echo $this->escape($user->yearDesc);       ?>
</span>

<span>- Region:
<?php 
    if ($user->unitId) echo $this->escape($user->unitDesc);
    else               echo 'None Selected';
?>
</span>

<span>- User:
<?php 
    if ($user->isMember) {
        $name = $user->name;
        if ($user->isPerson) $name .= '*';
    }
    else $name = 'Not Logged In';
    echo $this->escape($name);
?>
</span>
</div> <!-- Left Banner -->

<div id="layout-banner-right">
<span>
<?php 
    if ($user->isAuth) echo $this->href('Logout','home_logout');
    else               echo $this->href('Login', 'home_login');
?>
</span>
</div> <!-- Right Banner -->

</div> <!-- Banner -->
<div id="layout-menu-top" style="clear: both;">
<ul>
  <li>
    <?php if ($user->isMember) $home = 'account_index'; else $home = 'home_index'; ?>
    <a href="<?php echo $this->link($home); ?>">Home</a>
  </li>
<!--   
  <?php if ($this->context->session) { ?>
    <li><a href="#">Schedules</a></li>
  <?php } ?>
-->  
<!--
  <?php if ($user->isMember) { ?>
    <li>
      <a href="#">Accounts</a>
    </li>
  <?php } ?>
-->
  <?php if ($user->isMember) { ?>
    <li>
      <?php echo $this->href('Team Schedule','sched_div_list'); ?>
    </li>
  <?php } ?>
  
  <?php if ($user->isMember) { ?>
    <li>
      <?php echo $this->href('Referee Schedule','sched_ref_list'); ?>
    </li>
  <?php } ?>
  <?php if ($user->isMadisonReferee) { ?>
    <li>
      <?php echo $this->href('Ref Points 498','ref_points_madison'); ?>
    </li>
  <?php } ?>
  <?php if ($user->isMonroviaReferee) { ?>
    <li>
      <?php echo $this->href('Ref Points 894','ref_points_monrovia'); ?>
    </li>
  <?php } ?>  
  <?php if ($user->isReferee) { ?>
    <li>
      <?php echo $this->href('Area Tourn Avail','ref_avail_signup'); ?>
    </li>
  <?php } ?>
   <?php if ($user->isAdmin) { ?>
    <li>
      <a href="<?php echo $this->link('admin_index'); ?>">Admin</a>
    </li>
  <?php } ?>
</ul>
</div> <!-- Menu Top -->
<?php /*
<br />
<div id="top_menu">

<ul id="nav">

    <li><a href="#">Regular</a>
        <ul>
            <li><a href="#">Home Page</a></li>
            <li><a href="#">Logout</a></li>
            <li><a href="#">Clear Session</a></li>
            <li><a href="#">User Preferences</a></li>
        </ul>
    </li>
    <li><a href="#">Administration</a>
        <ul>
            <li><?php echo $this->href('List People',        'person_list');     ?></li>
            <li><?php echo $this->href('List Regions',       'unit_list');       ?></li>
            <li><?php echo $this->href('List Fields',        'field_list');      ?></li>
            <li><?php echo $this->href('List Field Sites',   'field_site_list'); ?></li>
            <li><?php echo $this->href('List Physical Teams','phy_team_list');   ?></li>
            <li><?php echo $this->href('List Schedule Teams','sch_team_list');   ?></li>
        </ul>
    </li>
</ul>
<br /><br />
</div>
<?php */ ?>

<div id="layout-content">
<?php echo $this->content; ?>
</div>

</div></body></html>