<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI\Plugins\Media\Media;
use Ponticlaro\Bebop\Common\Utils;

class FileUpload extends \Ponticlaro\Bebop\UI\Patterns\ModuleAbstract {

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl($el)
  {
    if ($el instanceof Media)
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
      'config' => [
        'modal_title'       => 'Upload or select existing files',
        'modal_button_text' => 'Add Files',
        'mime_types'        => [],
      ],
      'attrs'  => [],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload">',
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

    $this->el = new Media($this->getVar('name'), null, []);
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    $this->el->setName($this->getVar('name'));
    $this->el->setConfig('attrs', $this->getVar('attrs'));
    $this->el->setConfig('data', $this->getVar('value'));

    $config = $this->getVar('config');

    if ($config && is_array($config)) {
      foreach ($config as $key => $value) {
        $this->el->setConfig($key, $value);
      }
    }
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

    if ($name && isset($data[$name]))
      $this->setVar('value', is_array($data[$name]) ? ($data[$name] ? reset($data[$name]) : null) : $data[$name]);
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