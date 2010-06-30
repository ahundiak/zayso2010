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

</head>
<body id="layout-body"><div id="layout-page">

<div id="layout-content">
<?php echo $this->content; ?>
</div>

</div></body></html>