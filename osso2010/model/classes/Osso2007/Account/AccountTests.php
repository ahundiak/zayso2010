<?php
class Osso2007_Account_AccountTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso2007_Account_AccountDirect';

  public function test_getCerts()
  {
    $direct = $this->direct;

    $result = $direct->getCerts(array('account_id' => 1));

    $this->assertTrue($result->success);

    $rows = $result->rows; // Cerad_Debug::dump($rows);
    $this->assertEquals(count($rows),3);

    $item = $rows[1];
    $this->assertEquals($item['eayso_fname'],'Arthur');

    return;
  }
}
?>
