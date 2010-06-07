<?php
class IndexController extends Controller
{
  function executeGet()
  {
    session_destroy();
		
    // Process the template
    $this->processTemplate('index.phtml');
  }
}
?>