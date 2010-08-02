<?php
class UserTest extends BaseTest
{
	function testUserInfoRead()
	{
		$action = new Direct_User_UserInfoAction($this->context);
		
		$results = $action->read(array('member_id' => 31)); // Ronnie
		
		$records = $results['records'];
		
		$this->assertEquals(count($records),1);
		$record = $records[0];
		
    // Cerad_Debug::dump($record);
		
		$this->assertEquals($record['account_name'],'rturrentine');
    $this->assertEquals($record['member_name' ],'Ronnie Turrentine');
		
	}
	function testUserSignInLoad()
  {
    $action = new Direct_User_UserSignInAction($this->context);
    
    $results = $action->load();
    
    $this->assertEquals('ahundiak',$results['data']['user_name']);
    
  }
  function testUserSignInSubmit()
  {
    $action = new Direct_User_UserSignInAction($this->context);
    
    // Normal login
    $params = array('user_name' => 'ahundiak','user_pass' => 'qwepoi');
    $results = $action->submit($params);
    $this->assertTrue  ($results['success']);
    $this->assertEquals($results['member_id'],1);
    
    // Incorrect password
    $params = array('user_name' => 'ahundiak','user_pass' => 'qwepoiX');
    $results = $action->submit($params);
    $this->assertFalse ($results['success']);
    $this->assertEquals($results['errors']['user_pass'],'Missing or invalid password');
    
    // Missing password
    $params = array('user_name' => 'ahundiak','user_pass' => '');
    $results = $action->submit($params);
    $this->assertFalse ($results['success']);
    $this->assertEquals($results['errors']['user_pass'],'Missing or invalid password');
    
    // Incorrect account
    $params = array('user_name' => 'ahundiakx','user_pass' => 'qwepoi');
    $results = $action->submit($params);
    $this->assertFalse ($results['success']);
    $this->assertEquals($results['errors']['user_name'],'Missing or invalid user name');
    
  }
}
?>