<?php
class Osso2007_Div_DivTests extends Cerad_Tests_Base
{
  public function test_getIdForKey()
  {
    $repoDiv = $this->context->repos->div;

    $id = $repoDiv->getIdForKey('U10B');

    $this->assertEquals(7,$id);
  }
}
?>
