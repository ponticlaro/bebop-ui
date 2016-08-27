<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Html\Element;
use Ponticlaro\Bebop\Common\Utils;

class Button extends \Ponticlaro\Bebop\UI\Patterns\ModuleAbstract {

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl($el)
  {
    if ($el instanceof Element && $el->getTag() == 'button')
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
      'class'  => 'button',
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-button">',
      'after'  => '</div>'
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
    // Initialize main element
    $this->el = new Element([
      'tag' => 'button'
    ]);
  }

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public function configIsValid()
  {
    return $this->getVar('text') ? true : false;
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

    if ($class = $this->getVar('class'))
      $this->el->addClass($class);

    if ($text = $this->getVar('text'))
      $this->el->setText($text);
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
      $this->setVar('value', is_array($data[$name]) ? $data[$name][0] : $data[$name]);
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