<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Textarea extends \Ponticlaro\Bebop\Html\ControlElement {

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
    $this->setTag('textarea');

    // Apply configuration
    $this->applyConfig($config);
  }

  /**
   * Returns element HTML
   * 
   * @return string Element HTML
   */
  public function getHtml()
  {
    return $this->getOpeningTag() . $this->getValue() . $this->getClosingTag();
  }
}