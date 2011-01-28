<?php $title="Registration Page 2" ?>
<div>
  <p>At this point the user will be presented with a list of sicial networking sites (Facebook etc) and
    given the choice of picking one to use to for signing in to their zayso account.  Or they can just continue.
  </p>
<?php
  $url = 'index_login_rpx';
?>
<iframe
  src="http://zayso.rpxnow.com/openid/embed?token_url=<?php echo $url; ?>"
  scrolling="no"  frameBorder="no"  allowtransparency="true"  style="width:400px;height:240px">
</iframe>
</div>
