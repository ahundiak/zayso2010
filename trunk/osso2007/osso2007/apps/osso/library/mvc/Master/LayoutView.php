<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
           
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  
<title><?= $this->escape($this->title) ?></title>

<link rel="stylesheet" type="text/css" href="<?= $this->urlFile('css/layout.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= $this->urlFile('css/osso.css') ?>" />

</head>
<body id="layout-body">
<div  id="layout-page">

<div  id="layout-banner">

<div  id="layout-banner-left">

<span>Zayso</span>

<span>- Season: 
  <?= $this->escape($this->registry->user->seasonTypeDesc); ?>
  <?= $this->escape($this->registry->user->regYearDesc);    ?>
</span>

<span>- Region:
  <?php if ($this->registry->user->unitId) { ?>
    <?= $this->escape($this->registry->user->unitDesc); ?>
  <?php } else { ?>
    None Selected
  <?php } ?>
</span>

<span>- User:
<?php if ($this->registry->user->memberId) { ?>
  <?php 
    $userName = $this->registry->user->getName();
    if ($this->registry->user->personId) $userName .= '*';
   ?>
  <?= $this->escape($userName); ?>
<?php } else { ?>
  Not Logged In
<?php } ?>
</span>

</div> <!-- Left Banner -->

<div id="layout-banner-right">
<span>
  <?php if ($this->registry->user->memberId) { ?>
    <a href="<?= $this->urlAction('index/logout') ?>">Logout</a>
  <?php } else { ?>
    &nbsp;
  <?php } ?>
</span>
</div> <!-- Right Banner -->

</div> <!-- Banner -->

<div id="layout-menu-top" style="clear: both;">
<ul>
  <li><a href="<?= $this->urlAction('') ?>">Home</a></li>
  
  <?php if (isset($this->registry->session)) { ?>
    <li>
      <a href="<?= $this->urlAction('user/home') ?>">Schedules</a>
    </li>
  <?php } ?>
  
  <?php if ($this->registry->user->memberId) { ?>
  	<li>
      <a href="<?= $this->urlAction('account/home') ?>">Accounts</a>
    </li>
  <?php } ?>
  
  <?php if ($this->registry->user->isMember || 1) { ?>
  	<li>
      <a href="<?= $this->urlAction('sched_ref/list') ?>">Referee Schedule</a>
    </li>
  <?php } ?>
  
  <?php if ($this->registry->user->isMadisonReferee()) { ?>
  	<li>
      <a href="<?= $this->urlAction('points/home') ?>">Referee Points</a>
    </li>
  <?php } ?>
  
  <?php if ($this->registry->user->isAdmin()) { ?>
  	<li>
      <a href="<?= $this->urlAction('admin/home') ?>">Adminx</a>
    </li>
  <?php } ?>
</ul>
</div>

<div id="layout-content">
  <?php echo $this->content; ?>
</div>

</div></body></html>
