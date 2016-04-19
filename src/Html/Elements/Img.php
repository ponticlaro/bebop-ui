<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Img extends \Ponticlaro\Bebop\Html\ControlElement {

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
    $this->setTag('img');
    $this->setSelfClosing(true);

    // Apply configuration
    $this->applyConfig($config);
  }
}