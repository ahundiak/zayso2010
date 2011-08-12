<?php
/* ----------------------------------
 * Simple HTTP Response object loosely based on the Zend response
 *
 * The implementation is really minimal
 * There is some stuff that deals with a response code but I never send it
 * Want to add different content type headers such as js/css/csv/xml/json etc
 *
 * maybe setCSV
 */

namespace Cerad;

class Response
{
  protected $services = null;
  protected $webBase  = null;

  protected $headers = array();
  protected $body    = array();
  protected $code    = 200;

  protected $fileName = NULL;

  public function __construct($services)
  {
    // Not used
    $this->services = $services;

    // Decouple from Request object
    $this->webBase = $this->services->request->webBase;

    // $this->webPath =     dirname($_SERVER['SCRIPT_NAME']) . '/';
    // $this->webBase = 'http://' . $_SERVER['SERVER_NAME'] . $this->webPath;

    $this->init();
  }
  protected function init() {}
  
  public function setCode($code) { $this->code = $code; }
  
  public function setHeader($name,$value,$replace = false)
  {
    $this->headers[] = array('name' => $name, 'value'=> $value, 'replace' => $replace);
    return $this;
  }
  public function setRedirect($url, $code = 302)
  {
    // die('setRedirect ' . $url);

    // Should probably check for absolute
    if ((substr($url,0,5) != 'http:') && (substr($url,0,6) != 'https:'))
    {
      $url = $this->webBase . $url;
    }
    $this->setHeader('Location', $url, true);
    $this->setCode($code);
    return $this;
  }
  public function isRedirect()
  {
    $code = $this->code;
    if (($code >= 300) && ($code <= 307)) return true;

    return false;
  }
  public function setBody($content, $name = 'default')
  {
    $this->body[$name] = $content;
    return $this;
  }
  public function __toString()
  {
    ob_start();
    $this->sendBody();
    return ob_get_clean();
  }
  public function sendResponse()
  {
    $this->sendHeaders();
    $this->sendBody();
  }
  public function sendBody()
  {
    if ($this->isRedirect()) return;
    
    foreach($this->body as $content)
    {
      echo $content;
    }
  }
  // Don't understand the redirect logic here
  public function sendHeaders()
  {
    foreach($this->headers as $header)
    {
      header($header['name'] . ': ' . $header['value'], $header['replace']);
    }
  }
  public function setFileHeaders($fileName)
  {
    $this->fileName = $fileName;

    $this->setHeader('Pragma', 'public');
    $this->setHeader('Pragma', 'no-cache');

    $this->setHeader('Expires','Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  //$this->setHeader('Expires', '0');

    $this->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
    $this->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate');  // HTTP/1.1
    $this->setHeader('Cache-Control', 'pre-check=0, post-check=0, max-age=0'); // HTTP/1.1

    $this->setHeader('Content-Transfer-Encoding', 'none');

    if (strstr($fileName,'.xml'))
    {
      $this->setHeader('Content-Type', 'text/xml;');
      $this->setHeader('Content-Type', 'application/vnd.ms-excel;'); // This should work for IE & Opera
      $this->setHeader('Content-Type', 'application/x-msexcel');     // This should work for the rest
    }
    if (strstr($fileName,'.csv'))
    {
      $this->setHeader('Content-Type', 'text/csv;');
    }
    $this->setHeader('Content-Disposition', 'attachment; filename="'. $fileName .'"');
  }
  // Needed for transition
  public function appendBody($content, $name = null)
    {
    if ($name || $content)
    {
      // printf("appendBody name: '%s', content: '%s'\n",$name,$content);die();
    }
        if ((null === $name) || !is_string($name)) {
            if (isset($this->body['default'])) {
                $this->body['default'] .= (string) $content;
            } else {
                return $this->append('default', $content);
            }
        } elseif (isset($this->body[$name])) {
            $this->body[$name] .= (string) $content;
        } else {
            return $this->append($name, $content);
        }

        return $this;
    }
    public function append($name, $content)
    {
        if (!is_string($name)) return $this;

        if (isset($this->body[$name])) {
            unset($this->body[$name]);
        }
        $this->body[$name] = (string) $content;
        return $this;
    }
}
?>
