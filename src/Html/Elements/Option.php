<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Option extends \Ponticlaro\Bebop\Html\ControlElement {

  /**
   * Instantiates new HTML element object
   * 
   * @param array $config Element configuration
   */
  public function __construct(array $config = array())
  {
    // Initialize
    $this->init();

    // Set default configuration
    $this->setTag('option');

    // Apply configuration
    $this->applyConfig($config);
  }

  /**
   * Returns HTML for element attributes
   * 
   * @return string HTML for element attributes
   */
  public function getAttrsHtml()
  {
    // Making sure 'name' is not displayed on the `<option>` opening tag
    $name = $this->getAttr('name');
    $this->removeAttr('name');

    var_dump($this->config->getAll());

    $html = parent::getAttrsHtml();

    $this->setAttr('name', $name);

    return $html;
  }
}