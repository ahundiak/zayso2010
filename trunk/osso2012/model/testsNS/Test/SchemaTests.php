<?php
namespace Test;

class SchemaTests extends BaseTests
{
  function test1()
  {
    $em = $this->em;

    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

    $classes = array(
      $em->getClassMetadata('AYSO\Player\PlayerItem'),
    );

    $sqls = $tool->getCreateSchemaSql($classes);

    foreach($sqls as $sql)
    {
      // echo "\n" . $sql . "\n";
    }

    // $sql = $tool->updateSchema($classes);
  }
}
?>
