<?php
class Cerad_Repo_RepoResult
{
  protected $data;

  public function __construct($context = null)
  {
    $this->init();
  }
  protected function init()
  {
    $this->data = array
    (
      'success' => true,
      'message' => null,
      'errors'  => null,
      'rows'    => array(),
      'id'      => 0,
    );
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'id':
      case 'rows':
      case 'success':
      case 'message':
      case 'errors':
        return $this->data[$name];

      case 'count': 
        return count($this->data['rows']);

      case 'row':   
        if (count($this->data[rows]) != 1) return null;
        return $this->data['rows'][0];
    }
    die('Cerad_Repo_RepoResult __get ' . $name);
  }
  public function __set($name,$value)
  {
    switch($name)
    {
      case 'id':
      case 'rows':
      case 'success':
      case 'message':
        $this->data[$name] = $value;
        return;

      case 'error':
        $this->data['errors'][] = $value;
        $this->data['success'] = false;
        return;
    }
    die('Cerad_Repo_RepoResult __set ' . $name);
  }
}
?>
