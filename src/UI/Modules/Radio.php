<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Html\Elements\Radio as RadioElement;
use Ponticlaro\Bebop\Common\Utils;

class Radio extends \Ponticlaro\Bebop\UI\Patterns\ModuleAbstract {

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl($el)
  {
    if ($el instanceof RadioElement)
      $this->el = $el;

    return $this;
  }

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    // Set default vars
    $this->setVars([
      'options'        => [],
      'default_option' => '',
      'before'         => '<div class="bebop-ui-mod bebop-ui-mod-radio">',
      'after'          => '</div>'
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
    // If there is no name set it as a "slugified" label
    if ($this->getVar('label') && !$this->getVar('name'))
      $this->setVar('name', Utils::slugify($this->getVar('label')));

    // Initialize main element
    $this->el = new RadioElement([
      'attrs.name' => $this->getVar('name'), 
      'options'    => $this->getVar('options')
    ]);
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    if ($attrs = $this->getVar('attrs'))
      $this->el->setAttrs($attrs);

    if ($value = $this->getVar('value'))
      $this->el->setValue($value);

    if ($class = $this->getVar('class'))
      $this->el->addClass($class);
  }

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public function configIsValid()
  {
    return $this->getVar('name') ? true : false;
  }

  /**
   * Collects module form field values
   * 
   * @param  array  $data Array of existing form data
   * @return void
   */
  protected function __collectFormFieldValues(array $data = [])
  {
    $name = $this->getVar('name');

    if ($name && isset($data[$name]))
      $this->setVar('value', is_array($data[$name]) ? reset($data[$name]) : $data[$name]);

    // Use default_option if there is no value
    if (!$this->getVar('value') && $this->getVar('default_option'))
      $this->setVar('value', $this->getVar('default_option'));
  }

  /**
   * Template to render the main element for this module
   * 
   * @return void
   */
  public function renderMainTemplate()
  {   
    $this->el->render();
  }
}