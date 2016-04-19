<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI;
use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;
use Ponticlaro\Bebop\Common\Utils;

class ItemList extends \Ponticlaro\Bebop\UI\Patterns\ModuleAbstract {

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl(ContentList $el)
  {
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
      'config'     => [],
      'item_views' => [
        'browse'  => [],
        'reorder' => [],
        'edit'    => [],
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list">',
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
    // If there is no name set it as a "slugified" label
    if ($this->getVar('label') && !$this->getVar('name'))
      $this->setVar('name', Utils::slugify($this->getVar('label')));

    $this->el = new ContentList($this->getVar('name'), [], $this->getVar('config'));

    $item_views = $this->getVar('item_views');

    if ($item_views && is_array($item_views)) {
      foreach ($item_views as $view => $sections) {

        if ($sections && is_array($sections))
          $this->el->addItemViewSections($view, $sections);
      }
    }
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    if ($this->getVar('value'))
      $this->el->setData($this->getVar('value') ?: []);
  }

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public function configIsValid()
  {
    return $this->getVar('name') ? true : false;
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

    if ($name && isset($data[$name])) {

      $value = is_array($data[$name]) && isset($data[$name][0]) && is_array($data[$name][0]) ? $data[$name][0] : $data[$name];

      $this->setVar('value', $value);
    }
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