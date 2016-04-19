<?php

namespace Ponticlaro\Bebop\UI\Patterns;

use Ponticlaro\Bebop\Common\Collection;

abstract class ModuleAbstract {

  /**
   * Contains module main element
   * 
   * @var object
   */
  protected $el;

  /**
   * Variables collection 
   * 
   * @var object Ponticlaro\Bebop\Common\Collection
   */
  protected $vars;

  /**
   * Instantiates module
   * 
   * @param array $vars Array with module variables
   */
  final public function __construct(array $vars = [])
  {
    // Instantiate vars collection
    $this->vars = new Collection();

    // Initialize module configuration
    $this->__init();

    // Set custom vars
    $this->setVars($vars);

    // Modify after setting vars
    $this->__afterSetVars();
  }

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init() 
  {
    // Set default vars
    $this->setVars([
      'name'                        => '',
      'value'                       => '',
      'class'                       => '',
      'attrs'                       => [],
      'label'                       => '',
      'description'                 => '',
      'before'                      => '',
      'after'                       => '',
      'before_label'                => '',
      'after_label'                 => '',
      'before_main_el'              => '',
      'after_main_el'               => '',
      'before_description'          => '',
      'after_description'           => '',
      'render_before_main_template' => true,
      'render_after_main_template'  => true
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected abstract function __afterSetVars();

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public abstract function preRendering();

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public abstract function configIsValid();

  /**
   * Collects module form field values
   * 
   * @param  array  $data Array of existing form data
   * @return void
   */
  protected abstract function __collectFormFieldValues(array $data = []);

  /**
   * Template to render the main element for this module
   * 
   * @return void
   */
  public abstract function renderMainTemplate();

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl($el)
  {
    if (is_object($el))
      $this->el = $el;

    return $this;
  }

  /**
   * Returns module main element
   * 
   * @return object Module main element
   */
  final public function getEl()
  {
    return $this->el;
  }

  /**
   * Returns all variables
   * 
   * @return array All variables
   */
  final public function getAllVars()
  {
    return $this->vars->getAll();
  }

  /**
   * Returns the value for a single key
   * 
   * @param  string $key Target key or path
   * @return mixed       Value found on the target key or path
   */
  final public function getVar($key)
  {
    return is_string($key) ? $this->vars->get($key) : null;
  }

  /**
   * Sets a single module variable
   * 
   * @param string $key   Key or path that contains the value
   * @param mixed  $value Value to be set
   * @return object       This class instance
   */
  public function setVar($key, $value)
  {
    if (is_string($key)) {

      $current_data = $this->vars->get($key);

      if (is_array($current_data) && is_array($value)) {
        
        $this->vars->set($key, array_replace_recursive($current_data, $value));
      }

      else {

        $this->vars->set($key, $value);
      }
    }
      
    return $this;
  }

  /**
   * Sets a list of module variables
   * 
   * @param string $key   Key or path that contains the value
   * @param mixed  $value Value to be set
   * @return object       This class instance
   */
  final public function setVars(array $vars)
  {
    foreach ($vars as $key => $value) {
      $this->setVar($key, $value);
    }

    return $this;
  }

  /**
   * Pushes a single module variable to a list
   *
   * @param  string $key   Key or path that contains the value
   * @param  mixed  $value Value to be pushed
   * @return object        This class instance
   */
  final public function pushVar($key, $value)
  {
    if (is_string($key))
      $this->vars->push($value, $key);

    return $this;
  }

  /**
   * Pushes a list of module variables to a list
   * 
   * @param  string $key    Key or path that contains the values
   * @param  array  $values Values to be pushed
   * @return object         This class instance
   */
  final public function pushVars($key, array $values)
  {
    if (is_string($key))
      $this->vars->pushList($values, $key);

    return $this;
  }

  /**
   * Resets the module to its default state
   * 
   * @return object This class instance
   */
  final public function resetVars()
  {
    $this->vars->clear();
    $this->__init();

    return $this;
  }

  /**
   * Removes all configuration, including defaults
   * 
   * @return object This class instance
   */
  final public function clearVars()
  {
    $this->vars->clear();

    return $this;
  }

  /**
   * Template to render before module main element
   * 
   * @return void
   */
  public function renderBeforeMainTemplate()
  {
    if ($before = $this->getVar('before'))
      echo $before;

    if ($label = $this->getVar('label')) { 

      if ($before_label = $this->getVar('before_label'))
        echo $before_label;
      
      ?>
      
      <label class="bebop-ui-mod--label"><?php echo $label; ?></label>

      <?php 

      if ($after_label = $this->getVar('after_label'))
        echo $after_label;
    } 

    if ($before_main_el = $this->getVar('before_main_el'))
      echo $before_main_el;
  }

  /**
   * Template to render after module main element
   * 
   * @return void
   */
  public function renderAfterMainTemplate()
  {
    if ($after_main_el = $this->getVar('after_main_el'))
      echo $after_main_el;

    if ($description = $this->getVar('description')) {

      if ($before_description = $this->getVar('before_description'))
        echo $before_description;

      ?>

      <span class="bebop-ui-mod--description description"><?php echo $description; ?></span>

      <?php 
      
      if ($after_description = $this->getVar('after_description'))
          echo $after_description;
    }

    if ($after = $this->getVar('after'))
      echo $after; 
  }

  /**
   * Renders the module
   *
   * @return void
   */
  final public function render(array $data = [])
  {
    $this->__collectFormFieldValues($data);
    $this->preRendering();

    if ($this->configIsValid()) {

      if ($this->getVar('render_before_main_template'))
        $this->renderBeforeMainTemplate();

      $this->renderMainTemplate();

      if ($this->getVar('render_after_main_template'))
        $this->renderAfterMainTemplate();
    }
  }
}