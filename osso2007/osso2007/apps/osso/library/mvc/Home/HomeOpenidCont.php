<?php
/* -------------------------------------------------
 * This rather poorly names controller gets kicked off
 * when the user attempts to access a page for which they not authorized
 * 
 * Currently just redirects to the home page
 * 
 * Probably don't need anything fancier since the only way the user can really
 * get to the pages is if they type in urls
 */
class HomeOpenidCont extends Proj_Controller_Action
{
  public function processAction()
  {
    die('Open Id Controller');
  }
  public function processActionPost()
  {
    $this->getResponse()->setRedirect($this->link('home_index'));
  }
}
?>
