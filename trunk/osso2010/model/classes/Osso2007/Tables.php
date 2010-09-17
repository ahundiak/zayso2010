<?php
class Osso2007_Tables extends Cerad_Services
{
  protected $itemClassNames = array
  (
    'event'       => 'osso2007.event',
    'eventTeam'   => 'osso2007.event_team',
    'eventClass'  => 'osso2007.event_class',
    'eventPerson' => 'osso2007.event_person',
  );
  public function get($name,$cache = true)
  {
    if ($cache)
    {
      if (isset($this->items[$name])) return $this->items[$name];
    }
    if (!isset($this->itemClassNames[$name])) return null;

    $itemClassName = $this->itemClassNames[$name];
    $item = new Cerad_Repo_RepoTable($this->context->db,$itemClassName);

    if ($cache) $this->items[$name] = $item;

    return $item;
  }
}
?>
