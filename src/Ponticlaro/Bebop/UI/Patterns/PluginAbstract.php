<?php

namespace Ponticlaro\Bebop\UI\Patterns;

use Ponticlaro\Bebop\Common\Utils;

abstract class PluginAbstract implements PluginInterface {

	protected static $__key = '';

	public function __construct()
	{
		
	}

	public function load()
	{
		
	}

	public static function setKey($key)
	{
		if (!$key || !is_string($key)) return $this;
			
		$child_class         = get_called_class();
		$child_class::$__key = $key;
	}

	public static function getKey()
	{
		$child_class = get_called_class();

		if (!$child_class::$__key) {
			
			$class      = new \ReflectionClass($child_class);
			$class_name = $class->getShortName();
			$key        = Utils::slugify($class_name);

			$child_class::$__key = $key;
		}

		return $child_class::$__key;
	}

	public function setConfig($key, $value)
	{

	}

	public function setUI($key, $value) 
	{

	}

	public function render()
	{

	}
}