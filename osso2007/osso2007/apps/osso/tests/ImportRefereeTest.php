<?php
class ImportRefereeTest extends BaseAppTest
{
    function getUserName($fname,$lname)
    {
        $userName = substr($fname,0,1) . $lname;
        return $userName;
    }
    function addReferee($unitId,$fname,$lname,$uname)
    {
        $models = $this->context->models;

        // Bit of filtering
        if ($lname == 'lastname') return;
        if ($lname == NULL) return;
        if ($fname == NULL) return;
               
        // See if already have a person by that name
        $search = new SearchData();
        $search->unitId = $unitId;
        $search->lname = $lname;
        $search->fname = $fname;
        $search->exact = TRUE;
        
        $person = $models->PersonModel->searchOne($search);
        
        if ($person) {
            //echo "Have Volunteer Record For $fname $lname\n";
            
            //$models->VolModel->deleteForPersonId($person->id);
            //$models->PersonModel->delete($person->id);
            $personId = $person->id;
        }
        else {
            $person = $models->PersonModel->find(0);
            $person->unitId = $unitId;
            $person->fname = $fname;
            $person->lname = $lname;
            $person->status = 1;
            $personId = $models->PersonModel->save($person);
            echo "Added $fname $lname\n";
        }
        $search = new SearchData();
        $search->personId = $personId;
        $search->unitId   = $unitId;
        $search->seasonTypeId = 1;
        $search->regYearId  = 7;
        $search->volTypeId  = array($models->VolType->TYPE_ADULT_REF,$models->VolType->TYPE_YOUTH_REF);
        $vol = $models->VolModel->searchOne($search);
        
        if (!$vol) {
            $vol = $models->VolModel->find(0);
            $vol->personId = $personId;
            $vol->unitId   = $unitId;
            $vol->seasonTypeId = 1;
            $vol->regYearId    = 7;
            $vol->volTypeId  = $models->VolType->TYPE_ADULT_REF;
            $vol->divisionId = 0;
            $models->VolModel->save($vol);
        }
        
        // Check for account
        $search = new SearchData();
        $search->personId = $personId;
        $persons = $models->MemberModel->search($search);
        if (count($persons)) {
            //echo "Have account for $lname $fname\n";
            //return;
        }
        
        $search = new SearchData();
        $search->user = $uname;
        
        $account = $models->AccountModel->searchOne($search);
        if ($account) return;
            //$models->MemberModel->deleteForAccountId($account->id);
            //$models->AccountModel->delete($account->id);
        
        $account = $models->AccountModel->find(0);
        $account->user = $uname;
        $account->pass = md5('playon');
        $account->name = $lname;
        $account->status = 1;
        $accountId = $models->AccountModel->save($account);
        
        $member = $models->MemberModel->find(0);
        $member->accountId = $accountId;
        $member->personId  = $personId;
        $member->unitId = $unitId;
        $member->name   = $fname;
        $member->level  = 1;
        $member->status = 1;
        
        $memberId = $models->MemberModel->save($member);               
    }
    function test1()
    {
//        $this->addReferee(4,"Patrick","Streeter");
//        $this->addReferee(4,"Joe","Blow");
        
    }
    function test2()
    {
        $fp = fopen("MadisonReferees.csv",'rt');
        
        while($row = fgetcsv($fp)) {
            $this->addReferee(4,$row[2],$row[1],$row[0]);
        }
        fclose($fp);
    }
}

?>
