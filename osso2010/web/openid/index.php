<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<html>
  <head>
  </head>
  <body>
    <p>Instead of having to remember your zayso password you can now sign in using one or more of these accounts.
    </p>
     <iframe src="http://zayso.rpxnow.com/openid/embed?token_url=http%3A%2F%2Flocal.osso2010.org%2Fopenid%2Frpx.php"
             scrolling="no"  frameBorder="no"  allowtransparency="true"  style="width:400px;height:240px">
     </iframe>
    <br />
    <a class="rpxnow" onclick="return false;"
      href="https://zayso.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Flocal.osso2010.org/openid%2Frpx.php"> Sign In </a>
    
    <script type="text/javascript">
      var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
      document.write(unescape("%3Cscript src='" + rpxJsHost +
        "rpxnow.com/js/lib/rpx.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
  RPXNOW.overlay = true;
  RPXNOW.language_preference = 'en';
</script>
  </body>
</html>
