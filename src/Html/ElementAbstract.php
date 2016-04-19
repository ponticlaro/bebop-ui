<?php 

namespace Ponticlaro\Bebop\Html;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\Html;
use Ponticlaro\Bebop\Html\HtmlFactory;

abstract class ElementAbstract implements ElementInterface
{ 
  /**
   * Configuration for this element 
   * 
   * @var Ponticlaro\Bebop\Common\Collection
   */
  protected $config;

  /**
   * Instantiates new HTML element object
   * 
   * @param array $config Element configuration
   */
  public function __construct(array $config = [])
  { 
    // Initialize fundamental objects
    $this->init();

    // Apply config
    $this->applyConfig($config);
  }

  /**
   * Sets element defaults
   * 
   * @return void
   */
  protected function init()
  {
    // Instantiate configuration object
    $this->config = new Collection(array(
      'tag'             => null,
      'text'            => null,
      'attrs'           => [],
      'self_closing'    => false,
      'parent'          => null,
      'children'        => []
    ));
  }

  /**
   * Applies configuration array
   * 
   * @param  array  $config Configuration array
   * @return object         This class instance
   */
  public function applyConfig(array $config = [])
  {
    // Handle tag
    if (isset($config['tag']) && $config['tag']) {

      $this->setTag($config['tag']);
      unset($config['tag']);
    }

    // Handle text
    if (isset($config['text']) && $config['text']) {

      $this->setText($config['text']);
      unset($config['text']);
    }

    // Handle self_closing
    if (isset($config['self_closing']) && $config['self_closing']) {

      $this->setSelfClosing($config['self_closing']);
      unset($config['self_closing']);
    }

    // Handle attrs.class
    if (isset($config['attrs.class'])) {
      
      if (is_array($config['attrs.class'])) {
        
        $this->setClasses($config['attrs.class']);
      }

      else {

        $this->setClass($config['attrs.class']);
      }

      unset($config['attrs.class']);
    }

    // Handle attrs.id
    if (isset($config['attrs.id'])) {

      $this->setId($config['attrs.id']);
      unset($config['attrs.id']);
    }

    // Handle attrs.name
    if (isset($config['attrs.name'])) {

      $this->setName($config['attrs.name']);
      unset($config['attrs.name']);
    }

    // Handle attrs.value
    if (isset($config['attrs.value'])) {

      $this->setValue($config['attrs.value']);
      unset($config['attrs.value']);
    }

    // Handle attrs
    if (isset($config['attrs']) && is_array($config['attrs'])) {

      // Handle attrs.class
      if (isset($config['attrs']['class'])) {
        
        if (is_array($config['attrs']['class'])) {
          
          $this->setClasses($config['attrs']['class']);
        }

        else {

          $this->setClass($config['attrs']['class']);
        }

        unset($config['attrs']['class']);
      }

      // Handle attrs.id
      if (isset($config['attrs']['id'])) {

        $this->setId($config['attrs']['id']);
        unset($config['attrs']['id']);
      }

      // Handle attrs.name
      if (isset($config['attrs']['name'])) {

        $this->setName($config['attrs']['name']);
        unset($config['attrs']['name']);
      }

      // Handle attrs.value
      if (isset($config['attrs']['value'])) {

        $this->setValue($config['attrs']['value']);
        unset($config['attrs']['value']);
      }

      $this->setAttrs($config['attrs']);
      unset($config['attrs']);
    }

    // Handle parent
    if (isset($config['parent']) && $config['parent']) {

      $this->setParent($config['parent']);
      unset($config['parent']);
    }

    // Handle children
    if (isset($config['children']) && is_array($config['children'])) {

      foreach ($config['children'] as $el) {
        $this->append($el);
      }
      unset($config['children']);
    }

    // Handle remaining configuration elements
    if ($config) {
      foreach ($config as $key => $value) {
        $this->config->set($key, $value);
      }
    }

    return $this;
  }

  /**
   * Returns element factory ID
   * 
   * @return string Element factory ID
   */
  public function getFactoryId()
  {
    return HtmlFactory::getInstanceId($this);
  }

  /**
   * Sets element 'tag' name
   * 
   * @param string $tag Element 'tag' name
   */
  public function setTag($tag)
  {
    if (!is_string($tag))
      throw new \Exception('Element tag must be a string');

    $this->config->set('tag', Utils::slugify($tag));

    return $this;
  }

  /**
   * Returns element 'tag' name
   *  
   * @return string Element 'tag' name
   */
  public function getTag()
  {
    return $this->config->get('tag');
  }

  /**
   * Sets element text
   * 
   * @param string $text Element text
   */
  public function setText($text)
  {
    if (is_string($text))
      $this->config->set('text', $text);

    return $this;
  }

  /**
   * Returns element text
   *  
   * @return string Element text
   */
  public function getText()
  {
    return $this->config->get('text');
  }

  /**
   * Sets 'id' attribute
   * 
   * @param string $value Value to be assigned to the 'id' attribute
   */
  public function setId($value)
  { 
    if (is_string($value))
      $this->config->set('attrs.id', $value);

    return $this;
  }

  /**
   * Returns 'id' attribute
   * 
   * @return string Id attribute value
   */
  public function getId()
  {
    return $this->config->get('attrs.id');
  }

  /**
   * Adds several classes to the 'class' attribute
   * 
   * @param array $classes List of classes to be added
   */
  public function setClasses(array $classes)
  { 
    foreach ($classes as $class) {
      $this->addClass($class);
    }

    return $this;
  }

  /**
   * Adds a single class to the 'class' attribute
   * 
   * @param  string $class Class to be added
   * @return object        This class instance
   */
  public function addClass($class)
  { 
    if (is_string($class)) {

      $current_classes = $this->config->get('attrs.class');
      $this->config->set('attrs.class', $current_classes .' '. trim($class));
    }

    return $this;
  }

  /**
   * Removes a single class from the 'class' attribute
   * 
   * @param  string $class Class to be removed
   * @return object        This class instance
   */
  public function removeClass($class)
  {
    if (is_string($class)) {

      $current_str     = $this->config->get('attrs.class');
      $current_classes = explode(' ', $current_str);
      $class_key       = array_search(trim($class), $current_classes);

      if ($class_key !== false)
        unset($current_classes[$class_key]);

      $this->config->set('attrs.class', implode(' ', $current_classes));
    }

    return $this;
  }

  /**
   * Returns 'class' attribute
   * 
   * @return string 'Class' attribute value
   */
  public function getClass()
  {
    return $this->config->get("attrs.class");
  }

  /**
   * Sets value for 'name' attribute
   * 
   * @param string $name Value for 'name' atribute
   */
  public function setName($name)
  {
    if (is_string($name))
      $this->config->set('attrs.name', Utils::slugify($name));

    return $this;
  }

  /**
   * Returns 'name' attribute value
   * 
   * @return string 'Name' attribute value
   */
  public function getName()
  {
    return $this->config->get('attrs.name');
  }

  /**
   * Sets element 'value' attributes
   * 
   * @param mixed $value Value for 'value' atribute
   */
  public function setValue($value)
  {
    $this->config->set('attrs.value', $value);

    return $this;
  }

  /**
   * Returns element value
   * 
   * @return mixed Element value
   */
  public function getValue()
  {
    return $this->config->get('attrs.value');
  }

  /**
   * Sets several attributes
   * 
   * @param array $attrs List of attributes to set
   */
  public function setAttrs(array $attrs)
  {
    foreach ($attrs as $name => $value) {
      $this->setAttr($name, $value);
    }

    return $this;
  }

  /**
   * Sets a single attribute
   * 
   * @param string $name  Attribute name
   * @param mixed  $value Attribute value
   */
  public function setAttr($name, $value = null)
  {
    if (!is_string($name))
      throw new \Exception('Element attribute name must be a string');

    $this->config->set("attrs.$name", is_null($value) ? true : $value);

    return $this;
  }

  /**
   * Removes several target attributes
   * 
   * @param  array  $attrs List of attributes to be removed
   * @return object        This class instance
   */
  public function removeAttrs(array $attrs)
  {
    foreach ($attrs as $name) {
      $this->removeAttr($name);
    }

    return $this;
  }

  /**
   * Removes target attribute
   * 
   * @param  string $name Name for the target attribute to be removed
   * @return object       This class instance
   */
  public function removeAttr($name)
  {
    if (is_string($name))
      $this->config->remove("attrs.$name");

    return $this;
  }

  /**
   * Returns all attributes
   * 
   * @return array List of all attributes
   */
  public function getAttrs()
  {
    return $this->config->get('attrs') ?: [];
  }

  /**
   * Returns value for target attribute
   * 
   * @param  string $name Target attribute name
   * @return mixed        Value for target attribute
   */
  public function getAttr($name)
  {
    return is_string($name) ? $this->config->get("attrs.$name") : null;
  }

  /**
   * Sets element parent
   * 
   * @param ElementAbstract $el Parent element
   */
  public function setParent(ElementAbstract $el)
  {
    $this->config->set('parent', $el);

    return $this;
  }

  /**
   * Checks if element have a parent
   * 
   * @return boolean True if it has a parent, false otherwise
   */
  public function hasParent()
  {
    return $this->config->get('parent') ? false : true;
  }

  /**
   * Returns parent
   * 
   * @return object Parent element
   */
  public function getParent()
  {
    return $this->config->get('parent');
  }

  /**
   * Prepends a children to element
   * 
   * @param  mixed  $el HTML string or ElementAbstract
   * @return object     This class instance
   */
  public function prepend($el)
  {
    if (!$this->isSelfClosing()) {

      if (!is_string($el) && !$el instanceof ElementAbstract)
        throw new \Exception('Element children must be either strings or children of \Ponticlaro\Bebop\Html\ElementAbstract.');

      $this->config->unshift($el, 'children');
    }

    return $this;
  }

  /**
   * Appends a children to element
   * 
   * @param  mixed  $el HTML string or ElementAbstract
   * @return object     This class instance
   */
  public function append($el)
  {
    if (!$this->isSelfClosing()) {

      if (!is_string($el) && !$el instanceof ElementAbstract)
        throw new \Exception('Element children must be either strings or children of \Ponticlaro\Bebop\Html\ElementAbstract.');

      $this->config->push($el, 'children');
    }

    return $this;
  }

  /**
   * Returns all the element children
   * 
   * @return array All element children
   */
  public function getChildren()
  {
    return $this->config->get('children') ?: [];
  }

  /**
   * Sets the value for self_closing
   * 
   * @param boolean $value Boolean stating if the element should have a closing tag
   */
  public function setSelfClosing($value)
  {
    if (is_bool($value))
      $this->config->set('self_closing', $value);

    return $this;
  }

  /**
   * Checks if element does not have a closing tag
   * 
   * @return boolean True if there is no closing tag, false otherwise
   */
  public function isSelfClosing()
  {
    return $this->config->get('self_closing');
  }

  /**
   * Returns element opening tag 
   * 
   * @return string HTML for opening tag
   */
  public function getOpeningTag()
  {
    return '<'. $this->config->get('tag') . $this->getAttrsHtml() . ($this->isSelfClosing() ? '' : '>');
  }

  /**
   * Returns element closing tag, if not a self-closing element
   * 
   * @return string HTML for closing tag
   */
  public function getClosingTag()
  {
    return $this->isSelfClosing() ? '/>' : '</'. $this->config->get('tag') . '>';
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
   * Returns element children HTML
   * 
   * @return string Element children HTML
   */
  public function getChildrenHtml()
  { 
    $html = '';

    if (!$this->isSelfClosing()) {

      $children = $this->config->get('children') ?: [];

      if ($children) {
        foreach ($children as $child) {
          
          if (is_string($child)) {
            
            $html .= $child;
          }

          else {

            $html .= $child->getHtml();
          }
        }
      }
    }

    return $html;
  }

  /**
   * Returns element HTML
   * 
   * @return string Element HTML
   */
  public function getHtml()
  {
    return $this->getOpeningTag() . $this->getText() . $this->getChildrenHtml() . $this->getClosingTag();
  }

  /**
   * Renders element HTML
   * 
   * @return object This class object
   */
  public function render()
  {
    echo $this->getHtml();

    return $this;
  }

  /**
   * Returns element HTML if object is used as a string
   * 
   * @return string Element HTML
   */
  public function __toString()
  {
    return $this->getHtml();
  }
}