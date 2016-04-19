<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Form extends \Ponticlaro\Bebop\Html\ElementAbstract {

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
    $this->setTag('form');
    $this->setAttrs(array(
      'method' => '',
      'action' => ''
    ));

    // Apply configuration
    $this->applyConfig($config);
  }
}