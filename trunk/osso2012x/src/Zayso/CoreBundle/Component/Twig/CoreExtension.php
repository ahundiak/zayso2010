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
            'game_date' => new \Twig_Function_Method($this, 'gameDate'),
            'game_time' => new \Twig_Function_Method($this, 'gameTime'),
        );
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
}
?>
