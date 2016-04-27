<?php

namespace Ponticlaro\Bebop\UI\Plugins\ContentList;

use Ponticlaro\Bebop\Common\Collection;

class ContentListForm {

  /**
   * Form ID
   * 
   * @var string
   */
  private $id;

  /**
   * Form elements
   * 
   * @var object Ponticlaro\Bebop\Common\Collection
   */
  private $elements;

  /**
   * Instantiates class
   * 
   * @param string $id       Form ID
   * @param array  $elements Form elements
   */
  public function __construct($id, array $elements = array())
  {
    if (!is_string($id))
      throw new \Exception("Form ID must be a string");
    
    $this->id = $id;
    $this->elements = new Collection($elements);
  }

  /**
   * Returns form ID
   * 
   * @return string Form ID
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Adds form element
   * 
   * @param  string $id       Form element ID
   * @param  string $template HTML for form element
   * @return object           This class object
   */
  public function addElement($id, $template)
  {
    if (!is_string($id))
      throw new \Exception("Form Element ID must be a string");

    if (!is_string($template) && !is_array($template))
      throw new \Exception("Form Element Template must be a string or an array");

    $this->elements->set($id, $template);

    return $this;
  }

  /**
   * Adds multiple form elements
   * 
   * @param  array $elements List of form elements to add
   * @return object          This class object
   */
  public function addElements(array $elements)
  {
    foreach ($elements as $id => $template) {
      
      $this->addElement($id, $template);
    }

    return $this;
  }

  /**
   * Replace form element
   * 
   * @param string $id       Form element ID
   * @param string $template HTML for form element
   */
  public function replaceElement($id, $template)
  {
    $this->addElement($id, $template);

    return $this;
  }

  /**
   * Checks if form element with target ID already exists
   * 
   * @param  string  $id Element ID
   * @return boolean     True if it exists, false otherwise
   */
  public function hasElement($id)
  {
    return is_string($id) ? $this->elements->hasKey($id) : false;
  }

  /**
   * Checks if form elements list is empty
   * 
   * @return boolean True is empty, false otherwise
   */
  public function isEmpty()
  {
    return $this->elements->count() > 0 ? false : true;
  }

  /**
   * Removes form element with target ID
   * 
   * @param  string $id Element ID
   * @return object     This class object
   */
  public function removeElement($id)
  {
    if (!is_string($id))
      throw new \Exception("Form Element ID must be a string");

    $this->elements->remove($id);

    return $this;
  }

  /**
   * Removes form elements contained on the list of target IDs
   * 
   * @param  array  $ids List of IDs to remove
   * @return object      This class object
   */
  public function removeElements(array $ids)
  {
    foreach ($elements as $id => $template) {
      
      $this->removeElement($id);
    }

    return $this;
  }

  /**
   * Removes all existing form elements
   * 
   * @return object This class object
   */
  public function clearElements()
  {
    $this->elements->clear();
    
    return $this; 
  }

  /**
   * Returns the template for the form element with target ID
   * 
   * @param  string $id Element ID
   * @return string     Element template
   */
  public function getElement($id)
  {
    if (!is_string($id))
      throw new \Exception("Form Element ID must be a string");

    return $this->elements->get($id);
  }

  /**
   * Returns all form elements
   * 
   * @return array List of all elements
   */
  public function getAllElements()
  {
    return $this->elements->getAll();
  }
}