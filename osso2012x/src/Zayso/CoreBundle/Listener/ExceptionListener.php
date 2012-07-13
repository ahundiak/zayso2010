<?php
namespace Zayso\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

/* =======================================================================
 * This, in conjunction with a tagged entry on services.xml
 * Will get called on all kernal level exceptions
 */
class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        return;
        
        // We get the exception object from the received event
        $exception = $event->getException();
        $message = 'My Error says: ' . $exception->getMessage() . ' ' . get_class($exception);

        // Customize our response object to display our exception details
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode($exception->getStatusCode());

        // Send our modified response object to the event
        $event->setResponse($response);
    }
}
?>
