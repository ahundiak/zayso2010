<?php
/* -------------------------------------
 * 05 Sep 2010 - Obsolete
 */
require_once 'models/AccountModel.php';
require_once 'models/MemberModel.php';
require_once 'models/UnitModel.php';
require_once 'models/PersonModel.php';

class AccountController extends Mine_Controller_Action 
{
	public function homeAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$user     = $registry->user;
		$db       = $registry->db;
		
		$view->members = $members = UserModel::getAccountUsers($db,$user->accountId);
		
		$view->user  = $user;
		$view->title = 'Zayso Account Home';
		$this->render('account/AccountHomeView.php');
	}
	public function homePostAction()
	{
		$request = $this->getRequest();
		
		$memberCurrentIds = $request->getArray('member_current');
		$memberCurrentId  = 0;
		foreach($memberCurrentIds as $key => $value) {
			$memberCurrentId = $key;
		}
		if (!$memberCurrentId) return $this->redirect('account/home');
		
		/* Make a new user and shift to it */
		$registry = $this->registry;
		$config   = $registry->config;
		$db       = $registry->db;
		
		$user = UserModel::create($db,$config->user->asArray(),$memberCurrentId);
		if (!$user) return $this->redirect('account/home');
		
		$registry->session->user = $user;
		return $this->redirect('account/home');
	}
	public function addAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$user     = $registry->user;
		$db       = $registry->db;

		if (!isset($view->member)) $view->member = MemberTable::fetchNewRow($db);
		if (!isset($view->unitId)) $view->unitId = $user->unitId;
		
		$view->unitPickList = UnitTable::getPickList($db);
		
		$view->accountUser = $user->accountUser;
		$view->accountName = $user->accountName;
		
		$view->title = 'Zayso Account Add';
		$this->render('account/AccountAddView.php');		
	}
	public function addPostAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$user     = $registry->user;
		$db       = $registry->db;
		
		$member = MemberTable::fetchNewRow($db);
		
		$request = $this->getRequest();
		
		$member->unitId = $request->getId('unit_id');
		
		$member->memberName = trim($request->getText('member_name'));
		
		$errors = NULL;
		if (!$member->unitId) {
			$errors[] = 'Must select an organization';
		}
		if (!$member->memberName) {
			$errors[] = 'Member name cannot be blank';
		}
		if (!$errors) {
			$member->accountId  = $user->accountId;
			$member->memberPass = NULL;
			$member->level  = 2;
			$member->status = 1;
			
			try {
				$member->save();
			}
			catch (PDOException $e) {
				$errors[] = $e->getMessage();	
			}
		}
		if (!$errors) return $this->redirect('account/home');
		
		$view->unitId = $member->unitId;
		$view->member = $member;
		$view->memberCreateErrors = $errors;
		
		return $this->addAction();
	}
	/* ------------------------------------------
	 * For the current member, link them to an
	 * internal account
	 */
	public function linkAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$user     = $registry->user;
		$db       = $registry->db;
		$session  = $registry->session;
		
		$accountId = $this->_getParam('id');
		if (!$accountId) {
			if (!isset($session->accountLink)) return $this->redirect('account/home');
			
			$accountId     = $session->accountLink->accountId;
			
			if (isset($session->accountLink->errors)) {
				$view->errors  = $session->accountLink->errors;
			}
			if (isset($session->accountLink->message)) {
				$view->message = $session->accountLink->message;
			}
		}
		$account = AccountModel::getAccount($db,$accountId);
		if (!$account) return $this->redirect('account/home');
		
		$session->accountLink = $sessionParams = new SessionParams();
		$sessionParams->accountId = $accountId;
		 
		$members = UserModel::getAccountUsers($db,$accountId);

		foreach($members as $member) {
			$member->personPickList = $this->genPersonPickList(
			PersonModel::findForLastName($db,$member->accountName,$member->unitId));
		}
		$view->account = $account;
		$view->members = $members;
		
		$view->title = 'Zayso Account Link';
		$this->render('account/AccountLinkView.php');
	}
	protected function genPersonPickList($persons)
	{
		$options = array();
		if (!$persons) return $options;
		
		foreach($persons as $person)
		{
			$options[$person->id] = $person->name;
		}
		return $options;	
	}
	public function linkPostAction()
	{
		$registry = $this->registry;
		$config   = $registry->config;
		$view     = $registry->view;
		$user     = $registry->user;
		$db       = $registry->db;
		
		$sessionParams = $registry->session->accountLink;
		
		/* People to link to */
		$request   = $this->getRequest();
		$personIds = $request->getArray('person_ids');

		if (!$personIds) return $this->redirect('account/home');

		/* Check Permission */
		$password = $request->getText('person_link_password');
		switch($password) {
			case 'soccer894':
			case 'xxx':
			//case NULL:
				break;
			default:
				$sessionParams->errors = 'Incorrect link password';
				return $this->redirect('account/link');
		}
		
		/* Simple update */
		$memberTable = new MemberTable($db);
		$currentUserUpdated = FALSE;
		foreach($personIds as $memberId => $personId)
		{
			$member = $memberTable->find($memberId);

			if (($member->memberId) && ($member->personId != $personId)) {
				$member->personId = $personId;
				$member->save();
		
				if ($user->memberId == $memberId) $currentUserUpdated = TRUE;
				
				//$sessionParams->message = 'Updated Link';
			}
		}			
		/* Update user if need be */
		if ($currentUserUpdated) {
			$user = UserModel::create($db,$config->user->asArray(),$user->memberId);
			$registry->session->user = $user;
		}
		$this->redirect('account/link');
	}
	public function listAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$db       = $registry->db;
		
		$view->title    = "Show Accounts";
		$view->accounts = AccountModel::getAccounts($db);
		
		/* Expand any passed account */
		$accountId = $this->_getParam('id');
		if ($accountId) {	
			$view->members = UserModel::getAccountUsers($db,$accountId);
		}
		$this->render('account/AccountListView.php');
	}
	public function listPostAction()
	{
		$registry = $this->registry;
		$view     = $registry->view;
		$db       = $registry->db;
		
		/* Accounts to delete */
		$request   = $this->getRequest();
		$deleteIds = $request->getArray('account_delete_ids');

		if (!$deleteIds) return $this->redirect('account/list');
		
		foreach($deleteIds as $deleteId => $value) {
			AccountModel::delete($db,$deleteId);
		}
		return $this->redirect('account/list');
	}
		
	public function editAction()
	{		
		/* Query for account */
		$accountId = $this->_getParam('id');
		$accountTable = new AccountTable();
		if ($accountId) $account = $accountTable->find($accountId);
		else {
			/* Redirect to some error page */
		}
		
		/* Setup view data */
		$view = $this->getView();
		
		$view->title   = "Edit Account";
		$view->account = $account;
		$view->error   = NULL;
		
		/* And render it */		
		$this->render('account/AccountEditView.php');
	}
	public function editActionPost()
	{
		$error = NULL;
				
		$post = new Mine_Filter_Input($_POST);
		
		$accountId = $post->getInt('account_id');
		
		$accountTable = new AccountTable();
		if ($accountId) $account = $accountTable->find($accountId);
		else {
			/* Redirect to some error page */
		}
		
		/* Pull the data */
		$account->username = $post->getRaw  ('account_username');
		$account->name     = $post->getName ('account_name');
		$account->email    = $post->getEmail('account_email');
		$account->orgId    = $post->getInt  ('account_org_id');
		$pass1             = $post->getRaw  ('account_userpass1');
		$pass2             = $post->getRaw  ('account_userpass2');
	
		if (!$account->username) {
			$error = "Account Name must be non-blank and unique";
		}
		if (!$account->name) $account->name = $account->username;
		
		if (!$error) {
			try {
				$account->save();
			}
			catch (PDOException $e) {
				$error = $e->getMessage();	
			}
		}
		if (!$error) {
			$this->redirect('account/edit/id/' . $account->accountId);
		}
		
		/* Redisplay the form with errors */
		$view = $this->getView();
		$view->title   = "Edit Account";
		$view->account = $account;
		$view->error   = $error;
		$this->render('account/AccountEditView.php');
	}
}
?>
