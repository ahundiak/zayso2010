<?php
namespace Zayso\CoreBundle\Component\Twig;


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
            'game_dow'       => new \Twig_Function_Method($this, 'gameDOW'),
            'game_date'      => new \Twig_Function_Method($this, 'gameDate'),
            'game_time'      => new \Twig_Function_Method($this, 'gameTime'),
            'game_teams'     => new \Twig_Function_Method($this, 'gameTeams'),
            'game_team_desc' => new \Twig_Function_Method($this, 'gameTeamDesc'),
        );
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
     * This assumes we have a schedule team with a key starting with
     * UxxB XX
     * 
     * The attached physical team is optional
     * Really a tournament team description
     */
    public function gameTeamDesc($gameTeam)
    {
        $schTeam = $gameTeam->getTeam();
        if (!$schTeam)
        {
            return $gameTeam->getType() . ' ' . 'No Schedule team';
        }
        $schTeamKey = substr($schTeam->getTeamKey(),8);
        
        $phyTeam = $schTeam->getParent();
        if (!$phyTeam) return $schTeamKey;
        
        $phyTeamKey = $phyTeam->getTeamKey();
        $region = substr($phyTeamKey, 0,5);
        $coach  = substr($phyTeamKey,10);
        
        return $schTeamKey . ' ' . $region . ' ' . $coach;
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