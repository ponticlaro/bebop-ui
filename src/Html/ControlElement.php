<?php 

namespace Ponticlaro\Bebop\Html;

use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\Html;

class ControlElement extends ElementAbstract {

	/**
	 * Sets element defaults
	 * 
	 * @return void
	 */
  protected function init()
  {
  	// Call parent function
  	parent::init();

  	// Set control element defaults
  	$this->config->setList([
      'multiple_values' => false,
      'options'         => []
  	]);
  }

  /**
   * Applies configuration array
   * 
   * @param  array  $config Configuration array
   * @return object         This class instance
   */
	public function applyConfig(array $config = [])
	{
    // Handle multiple_values
    if (isset($config['multiple_values']) && $config['multiple_values']) {

      $this->setMultipleValues($config['multiple_values']);
      unset($config['multiple_values']);
    }

    // Handle options
    if (isset($config['options']) && is_array($config['options'])) {

      $this->setOptions($config['options']);
      unset($config['options']);
    }

    // Handle option_element
    if (isset($config['option_element']) && $config['option_element']) {

      if (isset($config['option_element']['name']) && $config['option_element']['name']) {
        
        $this->setOptionElementName($config['option_element']['name']);
        unset($config['option_element']['name']);
      }

      if (isset($config['option_element']['selected_attr']) && $config['option_element']['selected_attr']) {
        
        $this->setOptionElementSelectedAttr($config['option_element']['selected_attr']);
        unset($config['option_element']['selected_attr']);
      }   
    }

   	// Call parent function
		parent::applyConfig($config);
	}

  /**
   * Sets several options
   * 
   * @param  array $options List of options to be set
   * @return object         This class instance
   */
  public function setOptions(array $options)
  {
    foreach ($options as $option) {
      
      if (is_string($option)) {
        
        $this->addOption($option, $option, false);
      }

      elseif (is_array($option) && isset($option['label']) && $option['label']) {
        
        $selected = isset($option['selected']) ? $option['selected'] : false;
        $this->addOption($option['label'], $option['value'], $selected);
      }
    }

    return $this;
  }

  /**
   * Sets a single option
   * 
   * @param string  $value    Option value
   * @param string  $label    Option label
   * @param boolean $selected Option selection status
   */
  public function addOption($label, $value = null, $selected = false)
  {
    if (!is_string($label))
      throw new \Exception('Option label must be a string');

    $this->config->push([
      'label'    => $label,
      'value'    => $value,
      'selected' => $selected
    ], 'options');

    return $this;
  }

  /**
   * Clears options list
   * 
   * @return object This class instance
   */
  public function clearOptions()
  {
    $this->config->set('options', []);

    return $this;
  }

  /**
   * Removes several options
   * 
   * @param  array  $options List of options to remove
   * @return object          This class instance
   */
  public function removeOptions(array $options)
  {
    foreach ($options as $value) {
      $this->removeOption($value);
    }

    return $this;
  }

  /**
   * Removes a single option, using its value
   * 
   * @param  string $value Option value to be removed
   * @return object        This class instance
   */
  public function removeOption($value)
  {
    if (!is_string($value))
      throw new \Exception('Option value must be a string');

    $options = $this->config->get('options') ?: [];

    if ($options) {   
      foreach ($options as $index => $option) {
        
        if ($option['value'] == $value)
           $this->config->remove("options.$index");
      }
    }

    return $this;
  }

  /**
   * Checks if element has options
   * 
   * @return boolean True if it has options, false otherwise
   */
  public function hasOptions()
  {
    return $this->config->get('options') ? true : false;
  }

  /**
   * Checks if element has multiple options
   * 
   * @return boolean True if it has multiple options, false otherwise
   */
  public function hasMultipleOptions()
  {
    return count($this->config->get('options')) > 1 ? true : false;
  }

  /**
   * Returns all options
   * 
   * @return array List with all element options
   */
  public function getOptions()
  {
    return $this->config->get('options') ?: [];
  }

  /**
   * Sets the value for multiple_values
   * 
   * @param  boolean $value Boolean stating if the element should allow multiple values
   * @return object         This class object
   */
  public function setMultipleValues($value)
  {
    if (is_bool($value))
      $this->config->set('multiple_values', $value);

    return $this;
  }

  /**
   * Checks if element allows multiple values to be selected
   * 
   * @return boolean True if it allows, false otherwise
   */
  public function allowsMultipleValues()
  {
    return $this->config->get('multiple_values') ? true : false;
  }

  /**
   * Returns HTML for element attributes
   * 
   * @return string HTML for element attributes
   */
  public function getAttrsHtml()
  {
    $html       = '';
    $attributes = $this->config->get('attrs') ?: [];

    foreach ($attributes as $key => $value) {

      if ($key == 'name' && $this->allowsMultipleValues())
        $value = $value .'[]';

      // Allow attributes without value
      if ($value === true) {
        
        $html .= ' '. $key;
      }

      // Handle attributes with value
      else {

        $html .= ' '. $key .'="'. $value .'"';
      }
    }

    return $html;
  }

  /**
   * Returns element options as HTML elements
   * 
   * @return array List of options as HTML elements
   */
  public function getOptionsAsElements()
  {
    return [];
  }
}