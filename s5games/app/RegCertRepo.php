<?php
class RegCertRepo
{
    const TYPE_SAFE_HAVEN         = 100;
    const TYPE_SAFE_HAVEN_REFEREE = 101;
    const TYPE_SAFE_HAVEN_COACH   = 102;

    const TYPE_REFEREE_BADGE              = 200;
    const TYPE_REFEREE_BADGE_U08          = 210;
    const TYPE_REFEREE_BADGE_ASSISTANT    = 220;
    const TYPE_REFEREE_BADGE_REGIONAL     = 230;
    const TYPE_REFEREE_BADGE_INTERMEDIATE = 240;
    const TYPE_REFEREE_BADGE_ADVANCED     = 250;
    const TYPE_REFEREE_BADGE_NATIONAL_2   = 280;
    const TYPE_REFEREE_BADGE_NATIONAL     = 290;

    const TYPE_COACH_BADGE                = 300;
    const TYPE_COACH_BADGE_U06            = 310;
    const TYPE_COACH_BADGE_U08            = 320;
    const TYPE_COACH_BADGE_U10            = 330;
    const TYPE_COACH_BADGE_U12            = 340;
    const TYPE_COACH_BADGE_INTERMEDIATE   = 350;
    const TYPE_COACH_BADGE_ADVANCED       = 360;
    const TYPE_COACH_BADGE_NATIONAL       = 370;
    
    protected $certs = array(
        0 => NULL,
        self::TYPE_SAFE_HAVEN_REFEREE         => 'Safe Haven Referee',
        self::TYPE_SAFE_HAVEN_COACH           => 'Safe Haven Coach',

        self::TYPE_REFEREE_BADGE_U08          => 'U08 Official',
        self::TYPE_REFEREE_BADGE_ASSISTANT    => 'Assistant Referee',
        self::TYPE_REFEREE_BADGE_REGIONAL     => 'Regional Referee',
        self::TYPE_REFEREE_BADGE_INTERMEDIATE => 'Intermediate Referee',
        self::TYPE_REFEREE_BADGE_ADVANCED     => 'Advanced Referee',
        self::TYPE_REFEREE_BADGE_NATIONAL_2   => 'National 2 Referee',
        self::TYPE_REFEREE_BADGE_NATIONAL     => 'National Referee',

        self::TYPE_COACH_BADGE_U06            => 'U06 Coach',
        self::TYPE_COACH_BADGE_U08            => 'U08 Coach',
        self::TYPE_COACH_BADGE_U10            => 'U10 Coach',
        self::TYPE_COACH_BADGE_U12            => 'U12 Coach',
        self::TYPE_COACH_BADGE_INTERMEDIATE   => 'Intermediate Coach',
        self::TYPE_COACH_BADGE_ADVANCED       => 'Advanced Coach',
        self::TYPE_COACH_BADGE_NATIONAL       => 'National Coach',
        
    );
    protected $certsx = array(
        0 => NULL,
        self::TYPE_SAFE_HAVEN_REFEREE         => 'Referee',
        self::TYPE_SAFE_HAVEN_COACH           => 'Coach',

        self::TYPE_REFEREE_BADGE_U08          => 'U08',
        self::TYPE_REFEREE_BADGE_ASSISTANT    => 'Assistant',
        self::TYPE_REFEREE_BADGE_REGIONAL     => 'Regional',
        self::TYPE_REFEREE_BADGE_INTERMEDIATE => 'Intermediate',
        self::TYPE_REFEREE_BADGE_ADVANCED     => 'Advanced',
        self::TYPE_REFEREE_BADGE_NATIONAL_2   => 'National 2',
        self::TYPE_REFEREE_BADGE_NATIONAL     => 'National',

        self::TYPE_COACH_BADGE_U06            => 'U06',
        self::TYPE_COACH_BADGE_U08            => 'U08',
        self::TYPE_COACH_BADGE_U10            => 'U10',
        self::TYPE_COACH_BADGE_U12            => 'U12',
        self::TYPE_COACH_BADGE_INTERMEDIATE   => 'Intermediate',
        self::TYPE_COACH_BADGE_ADVANCED       => 'Advanced',
        self::TYPE_COACH_BADGE_NATIONAL       => 'National',
        
    );
    function getDesc ($type) { return $this->certs [$type]; }
    function getDescx($type) { return $this->certsx[$type]; }
}
?>
