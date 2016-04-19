<?php

namespace Ponticlaro\Bebop\UI\Modules;

class RawHtml extends \Ponticlaro\Bebop\UI\Patterns\ModuleAbstract {

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
      'html'                        => '',
      'render_before_main_template' => false,
      'render_after_main_template'  => false
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
    // Nothing to do here
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    // Nothing to do here
  }

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public function configIsValid()
  {
    return $this->getVar('html') ? true : false;
  }

  /**
   * Collects module form field values
   * 
   * @param  array  $data Array of existing form data
   * @return void
   */
  protected function __collectFormFieldValues(array $data = [])
  {
    // Nothing to do here
  }

  /**
   * Template to render the main element for this module
   * 
   * @return void
   */
  public function renderMainTemplate()
  {
    if ($this->getVar('html'))
      echo $this->getVar('html');
  }
}