<?php
class Osso2007_Org_OrgTests extends Cerad_Tests_Base
{
  public function test_getIdForKey()
  {
    $repoOrg = $this->context->repos->org;

    $id = $repoOrg->getIdForKey('R0160');
    $this->assertEquals(7,$id);

    $id = $repoOrg->getIdForKey('498');
    $this->assertEquals(4,$id);
  }
}
?>
