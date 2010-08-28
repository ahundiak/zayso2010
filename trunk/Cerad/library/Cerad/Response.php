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
class Cerad_Response
{
  protected $headers = array();
  protected $body    = array();
  protected $code    = 200;

  public function isRedirect()
  {
    $code = $this->code;
    if (($code >= 300) && ($code <= 307)) return true;

    return false;
  }
  public function setCode($code) { $this->code = $code; }
  
  public function setHeader($name,$value,$replace = false)
  {
    $this->headers[] = array('name' => $name, 'value'=> $value, 'replace' => $replace);
    return $this;
  }
  public function setRedirect($url, $code = 302)
  {
    $this->setHeader('Location', $url, true);
    $this->setCode($code);
    return $this;
  }
  public function setBody($content, $name = 'default')
  {
    $this->body[$name] = $content;
    return $this;
  }
  public function __toString()
  {
    ob_start();
    $this->sendResponse();
    return ob_get_clean();
  }
  public function sendResponse()
  {
    $this->sendHeaders();
    $this->sendBody();
  }
  public function sendBody()
  {
    // if ($this->isRedirect()) return;
    
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
    return;

    // This is closer to what zend had
    $code = $this->code;
    $codeSent = false;
    foreach($this->headers as $header)
    {
      if (!$codeSent)
      {
        header($header['name'] . ': ' . $header['value'], $header['replace'], $code);
        $codeSent = true;
      }
      else
      {
        header($header['name'] . ': ' . $header['value'], $header['replace']);
      }
    }
    if (!$codeSent) header('HTTP/1.1 ' . $code);
  }
}
?>
