<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class XMLTests extends PHPUnit_Framework_TestCase
{
  function dumpQuery($elements)
  {
    if (!is_null($elements)) 
    {
      foreach ($elements as $element) 
      {
        echo "<br/>[". $element->nodeName. "]";

        $nodes = $element->childNodes;
        foreach ($nodes as $node) 
        {
          // echo $node->nodeValue. "\n";
        }
      }
    }
  }
  // NatGames2010Schedule.xml
  function testQuery()
  {
    $file = 'xml/' . 'NatGames2010Schedule.xml';
    $doc = new DOMDocument();
    $doc->loadHTMLFile($file);

    $xpath = new DOMXpath($doc);

    // example 1: for everything with an id
    //$elements = $xpath->query("//*[@id]");

    // example 2: for node data in a selected id
    //$elements = $xpath->query("/html/body/div[@id='yourTagIdHere']");

    // example 3: same as above with wildcard
    // $elements = $xpath->query("*/div[@id='yourTagIdHere']");

    $query = "Worksheet";
    $elements = $xpath->query($query);
    $this->dumpQuery($elements);

    $query = "/html/body/div[@id='yourTagIdHere']";
    $elements = $xpath->query($query);
    $this->dumpQuery($elements);


  }
}
?>
