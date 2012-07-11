<?php
namespace Zayso\CoreBundle\Monolog;

use Monolog\Logger as BaseLogger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class Logger extends BaseLogger
{
    // Just for reference
    protected static $levelsx = array(
        100 => 'DEBUG',
        200 => 'INFO',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
    );
    public function __construct($name)
    {
        parent::__construct($name);
        
        $handler = new Handler(null);
        $this->pushHandler($handler);
    }
}
?>
