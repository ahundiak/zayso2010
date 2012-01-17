<?php
namespace Zayso\NatGamesBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

class RefStatsManager
{
    protected $em = null;
    protected $projectId = 0;
    
    public function getEntityManager() { return $this->em; }
    public function getProjectId()     { return $this->projectId; }
    
    public function __construct($em, $projectId = 0)   
    { 
        $this->em = $em; 
        $this->projectId = $projectId;
    }
    public function loadReferees()
    {
        $projectId = $this->getProjectId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('projectPerson');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');

        $qb->leftJoin('projectPerson.person',    'person');
        $qb->leftJoin('person.registeredPersons','registeredPerson');
        $qb->leftJoin('person.org',              'org');
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$qb->expr()->literal($projectId)));
        
        $query = $qb->getQuery();
        
      //die('DQL ' . $query->getSQL());
        return $query->getResult();        
    }
    protected $aysoids = array();
    protected function isDup($item)
    {
        $person = $item->getPerson();
        if (!$person) return true;
         
        $reg = $person->getAysoRegisteredPerson();
        if (!$reg) return true;
        
        $aysoid = $reg->getRegKey();
        if (!$aysoid) return true;
        
        if (isset($this->aysoids[$aysoid])) 
        {
            echo 'Dup aysoid: ' . $aysoid . "\n";
            return true;
        }
        $this->aysoids[$aysoid] = true;
        return false;
    }
    protected function checkDups($items)
    {
        $this->aysoids = array();
        $itemsx = array();
        foreach($items as $item)
        {
            if (!$this->isDup($item)) $itemsx[] = $item;
        }
        return $itemsx;
    }
    protected function filterItemsNotGoingToReferee($items)
    {
        $itemsx = array();
        foreach($items as $item)
        {
            $keep = true;
            
            $plans = $item->getPlans();
            switch($plans['attend'])
            {
                case 'NS':    break;  // Not Set
                case 'NA':    break;  // No answer but plans were set
                case 'Yes':   break;
                case 'Yesx':  break;  // If team attends
                case 'Maybe': break;
                case 'No':    $keep = false; break;
                
                default: die('Attend ' . $plans['attend']);
            }
             switch($plans['will_referee'])
            {
                case 'NS':    break;  // Not Set
                case 'NA':    break;  // No answer but plans were set
                case 'Yes':   break;
                case 'Yesx':  break;  // If team attends
                case 'Maybe': break;
                case 'No':    $keep = false; break;
                
                default: die('Attend ' . $plans['attend']);
            }
            
            if ($keep) $itemsx[] = $item;
        }
        
        return $itemsx;
    }
    protected $badges = array();
    protected function countBadge($item)
    {
        $person = $item->getPerson();
        if (!$person) return;
         
        $reg = $person->getAysoRegisteredPerson();
        if (!$reg) return;
        
        $badge = $reg->getRefBadge();
        if (!$badge) return;
        
        if (isset($this->badges[$badge])) $this->badges[$badge]++;
        else                              $this->badges[$badge] = 1;
    }
    public function countBadges($items)
    {
        $this->badges = array(
            'None'         => 0,
            'None???'      => 0,
            'Regional'     => 0,
            'Intermediate' => 0,
            'Advanced'     => 0,
            'National'     => 0,
            'National 2'   => 0,
        );
        
        foreach($items as $item)
        {
            $this->countBadge($item);
        }
        return $this->badges;
    }
    protected $states = array();
    protected function countState($item)
    {
        $person = $item->getPerson();
        if (!$person) return;
         
        $org = $person->getOrg();
        if (!$org) return;
        
        $state = $org->getState();
        if (!$state) $state = 'Unknown';
        
        if (isset($this->states[$state])) $this->states[$state]++;
        else                              $this->states[$state] = 1;
    }
    public function countStates($items)
    {
        $this->states = array(
            'AL' => 0,
            'AZ' => 0,
            'CA' => 0,
            'FL' => 0,
            'HI' => 0,
            'IA' => 0,
            'IL' => 0,
            'MI' => 0,
            'MO' => 0,
            'NC' => 0,
            'NM' => 0,
            'NV' => 0,
            'NY' => 0,
            'OR' => 0,
            'PA' => 0,
            'RI' => 0,
            'SC' => 0,
            'TN' => 0,
            'TX' => 0,
            'UT' => 0,
            'VA' => 0,
        );
        foreach($items as $item)
        {
            $this->countState($item);
        }
        return $this->states;
    }
    public function countRefForSure($items)
    {
        $count = 0;
        foreach($items as $item)
        {
            $plans = $item->getPlans();
            $willReferee = $plans['will_referee'];
            if ($willReferee == 'Yes') $count++;
        }
        return $count;
    }
    public function process()
    {
        $referees = $this->loadReferees();
        
        $referees = $this->checkDups($referees);
        
        echo 'Project Person Count: ' . count($referees) . "\n";
        
        $referees = $this->filterItemsNotGoingToReferee($referees);
        
        echo 'Referee for sure: ' . $this->countRefForSure($referees) . "\n";
        echo 'Might referee: ' . count($referees) . "\n";
        echo "\n";
        
        $badges = $this->countBadges($referees);
        $total = 0;
        foreach($badges as $key => $value)
        {
            echo sprintf("Badge %s %2d\n",$key,$value);
            $total += $value;
        }
        echo 'Badge Total: ' . $total . "\n";
        echo "\n";
        
        $states = $this->countStates($referees);
        $total = 0;
        foreach($states as $key => $value)
        {
            echo sprintf("State %s %2d\n",$key,$value);
            $total += $value;
        }
        echo 'State Total: ' . $total . "\n";
   }
}
?>
