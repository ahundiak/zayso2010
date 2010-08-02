<?php
$user = $this->context->user;
$userDesc = $user->name;

if ($user->isLoggedIn) $userDesc .= ' Is Logged in';
else                   $userDesc .= ' Is NOT Logged in';

$html = $this->context->html;

?>
<h3>Welcome the Zayso Home page</h3>
<p>Now go away <?php echo $html->escape($userDesc); ?></p>
<div>
  <a href="account-logout">Log Out</a>
</div>
Some uncontained text.




