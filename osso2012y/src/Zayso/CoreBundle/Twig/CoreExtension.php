<?php
namespace Zayso\CoreBundle\Twig;


class CoreExtension extends \Twig_Extension
{
    protected $env;
    
    public function getName()
    {
        return 'zayso_core_extension';
    }
    
    public function initRuntime(\Twig_Environment $env)
    {
        parent::initRuntime($env);
        $this->env = $env;
    }
    protected function escape($string)
    {
        return twig_escape_filter($this->env,$string);
    }
    public function getFunctions()
    {
        return array(
            'cache_dtg'       => new \Twig_Function_Method($this, 'cacheDTG'),
            
            'game_dow'        => new \Twig_Function_Method($this, 'gameDOW'),
            'game_date'       => new \Twig_Function_Method($this, 'gameDate'),
            'game_time'       => new \Twig_Function_Method($this, 'gameTime'),
            'game_teams'      => new \Twig_Function_Method($this, 'gameTeams'),
            'game_team_desc'  => new \Twig_Function_Method($this, 'gameTeamDesc'),
            'game_team_desc2' => new \Twig_Function_Method($this, 'gameTeamDesc2'),
            'game_team_desc3' => new \Twig_Function_Method($this, 'gameTeamDesc3'),
            
            'game_team_class'   => new \Twig_Function_Method($this, 'gameTeamClass'),
            'game_person_class' => new \Twig_Function_Method($this, 'gamePersonClass'),
            
            'person_org'       => new \Twig_Function_Method($this, 'personOrg'),
            'person_org_class' => new \Twig_Function_Method($this, 'personOrgClass'),
            'person_ref_badge' => new \Twig_Function_Method($this, 'personRefBadge'),
            'person_age'       => new \Twig_Function_Method($this, 'personAge'),
            'person_vol'       => new \Twig_Function_Method($this, 'personVol'),
            'person_aysoid'    => new \Twig_Function_Method($this, 'personAysoid'),

       );
    }
    public function personOrgClass($game,$person)
    {
        if (!$person->getId()) return null;
        
        switch($person->getAysoCertz()->getAysoid())
        {
            case '65069981': // Randy Chappel
                return null;
        }
        $desc = $person->getOrgz()->getDesc2();
        if (!$desc) return null;
        
        $area   = substr($desc,0,4);
        $region = substr($desc,5,5);
        foreach($game->getTeams() as $teamRel)
        {
            $team = $teamRel->getTeam();
            if (!$team) return null;
            
            $org = $team->getOrgDesc();
         
            if (!$org) return null;
            
            if (strpos($org,$region) !== false) return 'person-region-issue';
            if (strpos($org,$area  ) !== false) return 'person-area-issue';
            
        }
        return null;
    }
    public function personOrg($person)
    {
        $desc = $person->getOrgz()->getDesc2();
        if (!$desc) return '';
        return substr($desc,0,10);
    }
    public function personAge($person)
    {
        if (!$person->getId()) return;
        
        $gender = $person->getGender();
        $age    = 2012 - (int)substr($person->getDob(),0,4);
        
        if ($age > 100)
        {
            if ($gender) return $gender . '???';
            return '';
        }
        if ($age > 80) return $gender . '80+';
        if ($age > 70) return $gender . '70+';
        if ($age > 60) return $gender . '60+';
        if ($age > 50) return $gender . '50+';
        if ($age > 40) return $gender . '40+';
        if ($age > 30) return $gender . '30+';
        if ($age > 21) return $gender . '21+';
        
        return $gender . $age;
    }
    public function personRefBadge($person)
    {
        if (!$person->getId()) return;
        
        $badge = $person->getAysoCertz()->getRefBadge();
        
        switch($badge)
        {
            case 'Regional':     return 'REG';
            case 'Intermediate': return 'INT';
            case 'Advanced':     return 'ADV';
            case 'National':     return 'NAT';
            case 'National 2':   return 'NAT';
        }
        return $badge;
    }
    public function personAysoid($person)
    {
        if (!$person->getId()) return;

        return $person->getAysoCertz()->getAysoid();;
    }
    public function personVol($person)
    {
        if (!$person->getId()) return;

        $memYear   = $person->getAysoCertz()->getMemYear();
        
        if ($memYear < 2011) $memYear = 'VOL FORM ' . $memYear;
        else $memYear = '';
        
        $safeHaven = $person->getAysoCertz()->getSafeHaven();
        
        switch($safeHaven)
        {
            case 'Coach':
            case 'Referee':
            case 'AYSO':
            case 'Youth':
                $safeHaven = '';
                break;
            default:
                $safeHaven = 'NO SAFE HAVEN';
        }
        if (!$memYear && !$safeHaven) return '';
        
        return $memYear . ' ' . $safeHaven;
    }
    public function cacheDTG()
    {
        return date('Y-m-d H:i:s T',time());
    }
    public function gameDOW($date)
    {
        if (strlen($date) < 8) return $date;

        $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));

        return date('D',$stamp);
    }
    public function gameDate($date)
    {
        if (strlen($date) < 8) return $date;

        $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));

        return date('D M d',$stamp);
    }
    public function gameTime($time)
    {
        switch(substr($time,0,2))
        {
            case 'BN': return 'BYE No Game';
            case 'BW': return 'BYE Want Game';
            case 'TB': return 'TBD';
        }
        $stamp = mktime(substr($time,0,2),substr($time,2,2));

        return date('h:i A',$stamp);
    }
    /* =====================================================
     * Generate a class for a game team suitable for high lighting
     */
    public function gameTeamClass($teamIds,$team)
    {
        $parent = $team->getParent();
        if (!$parent) return null;
        
        if (!in_array($parent->getId(),$teamIds)) return null;
        
        return 'team-hilite';
    }
    public function gamePersonClass($personIds,$person)
    {
        if (!$person) return null;
        
        if (!in_array($person->getId(),$personIds)) return null;
        
        return 'person-hilite';
    }
    /* =====================================================
     * This assumes we have a schedule team with a key starting with
     * UxxB XX
     * 
     * The attached physical team is optional
     * Really a tournament team description
     * 
     * gameTeamRel => gameTeam - Should always have a game team
     * 
     * gameTeamRel => gameTeam => null - Playoff game, no physical team issigned yet
     * gameTeamRel => gameTeam => phyTeam - Playoff game with assigned physical team
     * 
     * gameTeamRel => gameTeam => poolTeam => null - Probably should not happen
     * gameTeamRel => gameTeam => poolTeam => phyTeam
     */
    
    public function gameTeamDesc($gameTeamRel)
    {
        $gameTeam = $gameTeamRel->getTeam();
        $game     = $gameTeamRel->getGame();
        
        $pool = trim($game->getPool());
        $poolLen = strlen($pool);
        if ($game->isPoolPlay()) $poolLen--;
        
        // Investigate is_a and namespaces
        // if (get_class($gameTeam) != 'Zayso\CoreBundle\Entity\Team') $gameTeam = $gameTeam->getTeam();
        
        if (!$gameTeam)
        {
            // Really should not be possible
            return $gameTeam->getType() . ' ' . 'No Game Team';
        }
        $gameTeamKey = $gameTeam->getKeyx();
        if (!$gameTeamKey) return 'No Game Team Key'; // $gameTeamKey = $gameTeam->getDesc(); // Pool keys stored here
        
        /* =================================================
         * Need for tournaments
         * Game Pool U10G SF1
         * Team Key  U10G SF1 A 1ST 
         * Return    A 1ST
         *
         * Might want to check against game pool?
         * But that requires a link back to the game which means the input would alway have to a gameTeamRel
        */
        
        $phyTeam = $gameTeam->getParentForType('physical');
        if (!$phyTeam) return trim(substr($gameTeamKey,$poolLen));
        
        $phyTeamKey = $phyTeam->getKeyx();
        if ($gameTeamKey == $phyTeamKey) return $gameTeamKey; // Non-pool games?, unique key?
        
        // Do I really need this? yep, see above
        $gameTeamKey = trim(substr($gameTeamKey,$poolLen));
        
        $region = substr($phyTeamKey, 0,5);
        $coach  = substr($phyTeamKey,10);
        
        return $gameTeamKey . ' ' . $region . ' ' . $coach;
    }
    /* ==========================================================
     * This is an attempt to make an overlap between a regular schedule
     * and a tourn schedule
     */
    public function gameTeamDesc2($gameTeamRel)
    {
        $gameTeam = $gameTeamRel->getTeam();
        $game     = $gameTeamRel->getGame();
        
        $pool = trim($game->getPool());
        $poolLen = strlen($pool);
        if ($game->isPoolPlay()) $poolLen--;
        
        if (!$gameTeam)
        {
            // Really should not be possible
            return $gameTeam->getType() . ' ' . 'No Game Team';
        }
        // This should be the norm for pool teams and eventually for all
        $gameTeamDesc = $gameTeam->getDesc1();
        if ($gameTeamDesc) return $gameTeamDesc;
        
        // This is the norm for the rest
        $gameTeamKey = $gameTeam->getKey();
        if ($gameTeamKey) return $gameTeamKey;
        
        // Should just return
        // 
        // Should mean it is pool play
        $poolTeam = $gameTeam->getParentForType('pool');
        if (!$poolTeam) return 'No Pool Found';
        $poolTeamKey = $poolTeam->getKey();
        
        // See if phyTeam has been assigned to pool team
        $phyTeam = $poolTeam->getParentForType('physical');
        if (!$phyTeam) return $poolTeamKey;
        $phyTeamKey = $phyTeam->getKey();
        
        return substr($poolTeamKey,8,2) . ' ' . $phyTeamKey;
    }
    /* ==========================================================
     * Try 3, for pool play games drop division from description
     */
    public function gameTeamDesc3($gameTeamRel)
    {
        $gameTeam = $gameTeamRel->getTeam();
        $game     = $gameTeamRel->getGame();
        
        $desc = $gameTeam->getDesc();
        
        if (!$game->isPoolPlay()) return $desc;
        
        return substr($desc,0,8) . substr($desc,13);
        
        $pool = trim($game->getPool());
        $poolLen = strlen($pool);
        if ($game->isPoolPlay()) $poolLen--;
        
        if (!$gameTeam)
        {
            // Really should not be possible
            return $gameTeam->getType() . ' ' . 'No Game Team';
        }
        // This should be the norm for pool teams and eventually for all
        $gameTeamDesc = $gameTeam->getDesc1();
        if ($gameTeamDesc) return $gameTeamDesc;
        
        // This is the norm for the rest
        $gameTeamKey = $gameTeam->getKey();
        if ($gameTeamKey) return $gameTeamKey;
        
        // Should just return
        // 
        // Should mean it is pool play
        $poolTeam = $gameTeam->getParentForType('pool');
        if (!$poolTeam) return 'No Pool Found';
        $poolTeamKey = $poolTeam->getKey();
        
        // See if phyTeam has been assigned to pool team
        $phyTeam = $poolTeam->getParentForType('physical');
        if (!$phyTeam) return $poolTeamKey;
        $phyTeamKey = $phyTeam->getKey();
        
        return substr($poolTeamKey,8,2) . ' ' . $phyTeamKey;
    }
    /* ---------------------------------------------------
     * This was an attemt to process both teams
     * Didn't really work all that well
     */
    public function gameTeams($game)
    {
        $homeGameTeam = $game->getHomeTeam();
        $awayGameTeam = $game->getAwayTeam();
        
        $homeSchTeam = $homeGameTeam->getTeam();
        $awaySchTeam = $awayGameTeam->getTeam();
        
        $homePhyTeam = $homeSchTeam->getParent();
        $awayPhyTeam = $awaySchTeam->getParent();
       
        $homePhyTeamKey = null;
        $awayPhyTeamKey = null;
        
        if ($homePhyTeam) 
        {
            $key = $homePhyTeam->getTeamKey();
            $region = substr($key, 0,5);
            $coach  = substr($key,10);
            $homePhyTeamKey = $region . ' ' . $coach;
         }
         if ($awayPhyTeam) 
         {
            $key = $awayPhyTeam->getTeamKey();
            $region = substr($key, 0,5);
            $coach  = substr($key,10);
            $awayPhyTeamKey = $region . ' ' . $coach;
         }
         $homeSchTeamKey = $homeSchTeam->getTeamKey();
         $awaySchTeamKey = $awaySchTeam->getTeamKey();
         
         if ($homePhyTeamKey) $homeSchTeamKey .= ' ' . $homePhyTeamKey;
         if ($awayPhyTeamKey) $awaySchTeamKey .= ' ' . $awayPhyTeamKey;
         
         $home1 = substr($homeSchTeamKey,0,8);
         $home2 = substr($homeSchTeamKey,8);
         $away2 = substr($awaySchTeamKey,8);
         
         $html = <<< EOT
<table>
    <tr><td>$home1</td><td>$home2</td><tr>
    <tr><td>&nbsp;</td><td>$away2</td><tr>
</table>
EOT;
         return $html;
         
         $awaySchTeamKey = '_______. ' . substr($awaySchTeamKey,8);
         
         return $homeSchTeamKey . '<br />' . $awaySchTeamKey;
   }
}
?>
