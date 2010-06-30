<?php
class Base_BaseQuery
{
	protected $context = NULL;
	
	public function __construct($context)
	{
		$this->context = $context;
	}
}
?>