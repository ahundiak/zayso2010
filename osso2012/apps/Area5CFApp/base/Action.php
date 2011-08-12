<?php
namespace Area5CFApp\base;

class Action extends \Cerad\Action
{
  protected $mustBeSignedIn = false;
  protected $mustBeAdmin    = false;
}
?>
