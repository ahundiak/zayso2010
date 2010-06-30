<?php
class Base_BaseItem
{
	protected $data = array();
	
	public function __construct($values = NULL, $map = array())
	{
		if ($values) $this->setValues($values,$map);
	}
	public function __set($name,$value)
	{
		if (array_key_exists($name,$this->data)) {
			$this->data[$name] = $value;
			return;
		}
		echo "Bad attribute name for set: {$name}\n";
	}
	public function __get($name)
	{
		if (array_key_exists($name,$this->data)) return $this->data[$name];
		
		return NULL;
	}
	// Pulls multiple values but only stores if one exists
	// Later might want to add a prefix stripper
	// Use map as a translator and see how that works
	public function setValues($values,$map = array())
	{
		foreach($values as $name => $value)
		{
			if (isset($map[$name])) 
			{
				$name = $map[$name];
			
        if (array_key_exists($name,$this->data)) {
          $this->data[$name] = $value;
        }
			}
		}
	}
}
?>