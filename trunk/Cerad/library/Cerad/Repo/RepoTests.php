<?php
class Cerad_Repo_RepoTest extends Cerad_Repo_RepoBase
{
  protected $tableName  = 'osso2007.project_org_team';

}
class Cerad_ServicesTest extends Cerad_Services
{
  protected $itemClassNames = array
  (
    'test' => 'Cerad_Repo_RepoTest',
  );
}
class Cerad_Repo_RepoTests extends Cerad_Tests_Base
{
  function test1()
  {
    $repo = new Cerad_Repo_RepoTest($this->context);

    $table = $repo->table;
    $table = $repo->table;

    $this->assertTrue(is_array($table->values));

 }
  function testRepos()
  {
    $repos = new Cerad_ServicesTest($this->context);
    $repo  = $repos->test;

    $table = $repo->table;

    $this->assertTrue(is_array($table->values));
    
    $repoClassName = $repos->getClassName('test');
    $this->assertEquals('Cerad_Repo_RepoTest',$repoClassName);

  }
  function testTable()
  {
    $table = new Cerad_Repo_RepoTable($this->context->db,'osso2007.project_org');
    $this->assertEquals($table->pk,'id');
    $this->assertEquals(count($table->values),6);

    // Cerad_Debug::dump($table->values);

    $data = array('project_id' => 28, 'org_id' => 7, 'this' => 'that');
    $datax = $table->merge($data);
    $this->assertEquals(count($datax),6);
    $this->assertEquals($datax['project_id'],28);

    $data2 = array('project_id' => 28, 'org_id' => 4, 'this' => 'that');
    $datax = $table->filter($data2);
    $this->assertEquals(count($datax),2);
    $this->assertEquals($datax['project_id'],28);

    $data['id'] = 13;
    $datax = $table->changes($data,$data2);
    $this->assertEquals( 2,count($datax));
    $this->assertEquals( 4,$datax['org_id']);
    $this->assertEquals(13,$datax['id']);

    $data2['org_id'] = 7;
    $datax = $table->changes($data,$data2);
    $this->assertEquals(null,$datax);

  }
  function testTableQuery()
  {
    $table = new Cerad_Repo_RepoTable($this->context->db,'osso2007.project_org');
    $fields = array('id');
    $wheres = array('project_id' => 28,'org_id' => 7);
    $rows = $table->query($fields,$wheres);

    $this->assertEquals(1,count($rows));
    $this->assertEquals(135,$rows[0]['id']);

    $fields = array('id','org_id');
    $wheres = array('project_id' => 28,'org_id' => array(4,7));
    $rows = $table->query($fields,$wheres);
    $row  = $rows[0];

    $this->assertEquals(2,count($rows));
    $this->assertEquals(132,$row['id']);
    $this->assertEquals(  4,$row['org_id']);

    $fields = array('id','org_id');
    $wheres = array('project_id' => array(28,35),'org_id' => array(4,7));
    $rows = $table->query($fields,$wheres);

    $this->assertEquals(2,count($rows));
    $this->assertEquals(132,$rows[0]['id']);
  }
}
?>
