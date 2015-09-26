<?php

namespace Ponticlaro\Bebop\UI\Plugins\Gallery;

use Ponticlaro\Bebop\Common\Collection;

class MediaType {

	/**
	 * Media type identifier
	 * 
	 * @var string
	 */
	protected $id;

	/**
	 * List of defined templates
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection
	 */
	protected $templates;

	/**
	 * List of required templates
	 * 
	 * @var array
	 */
	protected static $required_templates = ['browse','edit'];

	/**
	 * Instantiates a new Gallery
	 * 
	 * @param string $id        Media type identifier
	 * @param array  $templates Associative array with templates to be defined
	 */
	public function __construct($id, array $templates)
	{
		$this->setId($id);
		$this->templates = new Collection($templates);

		if (!$this->__isValid())
			throw new \Exception("Media type is invalid. You must defined all these templates: ". implode(',', static::$required_templates));
	}

	/**
	 * Returns template ID
	 * 
	 * @return string Template ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Sets template ID
	 * 
	 * @param string $id Template ID
	 */
	public function setId($id)
	{
		if (!is_string($id))
			throw new \Exception('$id must be a string');
		
		$this->id = $id;

		return $this;
	}

	/**
	 * Checks if template is already defined
	 * 
	 * @param  string  $id Template ID
	 * @return boolean     True if exists, false otherwise
	 */
	public function hasTemplate($id)
	{
		return $this->templates->hasKey($id);
	}


	/**
	 * Adds a new single template
	 * 
	 * @param  string $id       Template ID
	 * @param  string $template Absolute path to template
	 * @return object           This class object
	 */
	public function addTemplate($id, $template)
	{
		if ($this->hasTemplate($id))
			throw new \Exception("Template '$id' is already defined. Use replaceTemplate() if you want to replace the existing template");

		$this->__setTemplate($id, $template);

		return $this;
	}

	/**
	 * Replaces a single template
	 * 
	 * @param  string $id       Template ID
	 * @param  string $template Absolute path to template
	 * @return object           This class object
	 */
	public function replaceTemplate($id, $template)
	{
		$this->__setTemplate($id, $template);

		return $this;
	}

	/**
	 * Returns a single template
	 * 
	 * @param  string $id Template ID
	 * @return string     Absolute path to template
	 */
	public function getTemplate($id)
	{
		if (!$this->hasTemplate($id))
			throw new \Exception("Template '$id' is not defined");

		return $this->templates->get($id);
	}

	/**
	 * Returns the full list of defined templates
	 * 
	 * @return array Full list of templates
	 */
	public function getAllTemplates()
	{
		return $this->templates->getAll();
	}

	/**
	 * Sets a single template
	 * 
	 * @param  string $id       Template ID
	 * @param  string $template Absolute path to template
	 * @return object           This class object
	 */
	protected function __setTemplate($id, $template)
	{
		if (!is_string($id))
			throw new \Exception('Template $id must be a string');
			
		if (!is_string($template) || !is_readable($template) || !is_file($template))
			throw new \Exception('Template $template must be a string and a readable file');

		$this->templates->set($id, $template);

		return $this;
	}

	/**
	 * Checks if the the object have all it needs to be usable
	 * 
	 * @return boolean True if valid, false otherwise
	 */
	protected function __isValid()
	{
		$is_valid              = true;
		$defined_templates_ids = $this->templates->getKeys();

		foreach (static::$required_templates as $template_id) {
			
			if (!in_array($template_id, $defined_templates_ids)) {
				
				$is_valid = false;
				break;
			}
		}

		return $is_valid;
	}
}