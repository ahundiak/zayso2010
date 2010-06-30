<?php

class Cerad_Template_HTML
{
  public $title = 'Default Title';
  public $page  = 'Default Page';
  public $content;

  public function process($name)
  {
    ob_start();
    include $name;
    return ob_get_clean();
  }
  public function processPage($name)
  {
    $this->content = $this->process($name);

    return $this->process($this->page);
  }
}
