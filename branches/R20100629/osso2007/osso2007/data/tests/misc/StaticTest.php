<?php
class MyClass
{
    const TYPE = 27;
    
    public function __get($name)
    {
        $constName = "self::$name";
        
        if (defined($constName)) return constant($constName);
    }
}
class StaticTest extends BaseTest
{
    function test1() { 
        $obj = new MyClass();
        $this->assertEquals($obj->TYPE, 27);
        $this->assertEquals($obj->TYPEx,NULL);
    }
}
?>
