<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2009/');

ini_set('include_path','.' .      
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'ZendFramework-1.8.2/library'      
);
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$status = "Empty";
if (isset($_POST['openid_action']) &&
    $_POST['openid_action'] == "login" &&
    !empty($_POST['openid_identifier'])) {

    $consumer = new Zend_OpenId_Consumer();
    if (!$consumer->login($_POST['openid_identifier'])) {
        $status = "OpenID login failed.";
    }
} else if (isset($_GET['openid_mode'])) {
    if ($_GET['openid_mode'] == "id_res") {
        $consumer = new Zend_OpenId_Consumer();
        if ($consumer->verify($_GET, $id)) {
            $status = "VALID " . htmlspecialchars($id);
        } else {
            $status = "INVALID " . htmlspecialchars($id);
        }
    } else if ($_GET['openid_mode'] == "cancel") {
        $status = "CANCELLED";
    }
}
?>
<html><body>
<?php echo "$status<br>" ?>
<form method="post">
<fieldset>
<legend>OpenID Login</legend>
<input type="text" name="openid_identifier" value="" size="40" />
<input type="submit" name="openid_action" value="login"/>
</fieldset>
</form>
</body></html>