<?php 

namespace Ponticlaro\Bebop\Html\Elements;

use Ponticlaro\Bebop\Html;

class Select extends \Ponticlaro\Bebop\Html\ControlElement {

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
    $this->setTag('select');

    // Apply configuration
    $this->applyConfig($config);
  }

  /**
   * Checks if element allows multiple values to be selected
   * 
   * @return boolean True if it allows, false otherwise
   */
  public function allowsMultipleValues()
  {
    return $this->config->get('attrs.multiple') ? true : false;
  }

  /**
   * Returns HTML for element attributes
   * 
   * @return string HTML for element attributes
   */
  public function getAttrsHtml()
  {
    // Making sure 'value' is not displayed on the `<select>` opening tag
    $value = $this->getAttr('value');
    $this->removeAttr('value');

    $html = parent::getAttrsHtml();

    $this->setAttr('value', $value);

    return $html;
  }

  /**
   * Returns element options as HTML elements
   * 
   * @return array List of options as HTML elements
   */
  public function getOptionsAsElements()
  {
    $elements = [];
    $value    = $this->getValue();
    $options  = $this->config->get('options') ?: [];

    foreach ($options as $key => $option) {

      $config = [
        'text'        => $option['label'],
        'attrs.name'  => $this->getName(),
        'attrs.value' => $option['value']
      ];

      $option_is_current = is_array($value) && in_array($option['value'], $value) || $option['value'] === $value ? true : false;

      if ($option_is_current || $option['selected'])
        $config['attrs']['selected'] = true;

      $elements[] = Html::Option($config);
    }

    return $elements;
  }

  /**
   * Returns element HTML
   * 
   * @return string Element HTML
   */
  public function getHtml()
  {
    $html     = $this->getOpeningTag();
    $elements = $this->getOptionsAsElements();

    if ($elements) {
      foreach ($elements as $el) {
        $html .= $el->getHtml();
      }
    }

    $html .= $this->getClosingTag();

    return $html;
  }
}