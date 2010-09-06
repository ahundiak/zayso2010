<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
<head>

<?php 
    if ($this->tplRedirectDelay && $this->tplRedirectLink) {
    echo "<meta http-equiv=\"refresh\" content=\"{$this->tplRedirectDelay};url={$this->tplRedirectLink}\" />";
    }
?>        
<title><?php echo $this->escape($this->tplTitle); ?></title>

<base href="<?php echo $this->context->request->webBase; ?>" />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/layout.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/osso.css');   ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->file('css/menu.css');   ?>" />


<link rel="stylesheet" type="text/css" href="<?php echo $this->file('../tools/jquery/css/smoothness/jquery-ui-1.8.2.custom.css'); ?>" />

<script type="text/javascript" src="<?php echo $this->file('../tools/jquery/js/jquery-1.4.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $this->file('../tools/jquery/js/jquery-ui-1.8.2.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $this->file('../tools/jquery/js/jquery.form.js'); ?>"></script>

</head>
<body id="layout-body"><div id="layout-page">

<div  id="layout-banner">

<?php $user = $this->context->user; ?>

<div  id="layout-banner-left">

<span>Zayso</span>

<span>
<?php 
    if ($user->isMember) {
        $name = $user->name;
        // if ($user->isPerson) $name .= '*';
        $name .= ' ' . $user->certs;
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
  <?php if ($user->isMadisonReferee && 1) { ?>
    <li>
      <?php echo $this->href('Ref Points 498','ref_points_madison'); ?>
    </li>
  <?php } ?>
  <?php if ($user->isMonroviaReferee && 1) { ?>
    <li>
      <?php echo $this->href('Ref Points 894','ref_points_monrovia'); ?>
    </li>
  <?php } ?>  
  <?php if ($user->isReferee && 0) { ?>
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