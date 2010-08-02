<?php
class Osso_User_UserMenuAction extends Osso_Base_BaseAction
{
  function execute($args)
  {
    $result = $this->result;
    $user   = $this->context->user;

    if (!$user->isLoggedIn)
    {
      $items = array
      (
        array('label' => 'Welcome',      'url' => 'tab/welcome'),
        array('label' => 'Field Status', 'url' => 'tab/field-status'),
        array('label' => 'Training',     'url' => 'tab/training'),
        array('label' => 'Schedules',    'url' => 'tab/schedule-guest'),
      );
      $result->items = $items;
      return $result;
    }
    $items = array
    (
      array('label' => 'Home',         'url' => 'tab/home'),
      array('label' => 'Field Status', 'url' => 'tab/field-status'),
      array('label' => 'Training',     'url' => 'tab/training'),
    );
    $result->items = $items;

    return $result;
  }
}
?>
