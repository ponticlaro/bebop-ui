<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Input extends \Ponticlaro\Bebop\Html\ControlElement {
  
  /**
   * Instantiates new HTML element object
   * 
   * @param array $config Element configuration
   */
  public function __construct(array $config = array())
  {
    // Initialize
    $this->init();

    // Set defaults
    $this->setTag('input');
    $this->setAttr('type', 'text');
    $this->setSelfClosing(true);

    // Apply configuration
    $this->applyConfig($config);
  }
}