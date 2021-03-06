<?php 

namespace Ponticlaro\Bebop\Html\Elements;

class Radio extends \Ponticlaro\Bebop\Html\ControlElement {

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
    $this->setTag('input');
    $this->setAttr('type', 'radio');
    $this->setSelfClosing(true);

    // Apply configuration
    $this->applyConfig($config);
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

    foreach ($options as $option) {

      $config = [
        'text'        => $option['label'],
        'attrs.name'  => $this->getName(),
        'attrs.value' => $option['value'],
        'attrs.id'    => $this->getId() .'radio_'. $this->getName() .'_'. $option['value']
      ];
      
      $option_is_current = is_array($value) ? in_array($option['value'], $value) : $option['value'] == $value;

      if ($option_is_current || $option['selected'])
        $config['attrs']['checked'] = true;

      $elements[] = new Radio($config);
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
    $html     = '';
    $elements = $this->getOptionsAsElements();

    if ($elements) {
      foreach ($elements as $el) {
        $html .= $el->getHtml();
      }
    }

    else {

      $html .= $this->getOpeningTag();
      $html .= $this->getClosingTag();
      $html .= ' <label for="'. $this->getId() .'">'. $this->getText() .'</label><br>';
    }

    return $html;
  }
}