<?php
class Osso_Org_OrgTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso_Org_OrgDirect';

  public function testPushAndPop()
  {
    $stack = array();
    $this->assertEquals(0, count($stack));

    array_push($stack, 'foo');
    $this->assertEquals('foo', $stack[count($stack)-1]);
    $this->assertEquals(1, count($stack));

    $this->assertEquals('foo', array_pop($stack));
    $this->assertEquals(0, count($stack));
  }
  public function test_getOrgForKey()
  {
    $direct = $this->direct;

    $search = array('keyx' => 'R0894');
    $results = $direct->getOrgForKey($search);

    $this->assertTrue($results['success']);
    $data = $results['row'];

    $this->assertEquals($data['desc1'],'R0894 Monrovia, AL');

    $search = array('keyx' => 160);
    $results = $direct->getOrgForKey($search);

    $this->assertTrue($results['success']);
    $data = $results['data'];

    $this->assertEquals($data['desc1'],'R0160 Huntsville, AL');
    
  }
  public function test_getOrgGroupOrgPicklist()
  {
    $direct = $this->direct;

    $search = array('id' => 1);
    $resultsId = $direct->getOrgGroupOrgPicklist($search);
    $this->assertTrue($resultsId['success']);

    $search = array('keyx' => 'ALL');
    $resultsKeyx = $direct->getOrgGroupOrgPicklist($search);
    $this->assertTrue($resultsKeyx['success']);

    $recordsId   = $resultsId  ['records'];
    $recordsKeyx = $resultsKeyx['records'];
    $this->assertTrue(count($recordsId) > 0);

    $this->assertEquals(count($recordsId),count($recordsKeyx));

    $index894 = -1;
    foreach($recordsId as $index => $record)
    {
      if ($record['id'] == 1) $index894 = $index;
    }
    $this->assertTrue($index894 >= 0);
    $this->assertEquals($recordsId  [$index894]['value'],'R0894 Monrovia, AL');
    $this->assertEquals($recordsKeyx[$index894]['value'],'R0894 Monrovia, AL');
    
  }
}
?>
