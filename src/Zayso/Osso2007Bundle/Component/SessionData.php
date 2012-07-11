<?php
class SessionData
{
    public function __construct() { die('Session Data'); }

    public function __get($name) { return NULL; }
}
?>
