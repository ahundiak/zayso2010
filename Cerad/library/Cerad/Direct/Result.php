<?php
class Cerad_Direct_Result extends Cerad_Array
{
  protected function init()
  {
    // Always have some preset values
    if (!$this->offsetExists('success')) $this->offsetSet('success',true);
  }
  // Errors are stored as arrays
  function offsetGet($name)
  {
    switch($name)
    {
      case 'record' : return $this->offsetGet('row');  break;
      case 'records': return $this->offsetGet('rows'); break;

      case 'rowCount':
      case 'recordCount':
        return count($this->offsetGet('rows'));
        break;

      case 'errorCount':
        return count($this->offsetGet('errors'));
        break;
   }
    return parent::offsetGet($name);
  }
  function offsetSet($name,$value)
  {
    switch($name)
    {
      case 'error': // Store internally as an array
        $errors = $this->offsetGet('errors');
        if (!is_array($errors)) $errors = array();
        $errors[] = $value;
        $this->offsetSet('errors',$errors);
        $this->offsetSet('success',false);
        return;
        break;
    }
    return parent::offsetSet($name,$value);
  }
}

?>
