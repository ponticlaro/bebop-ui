<?php

namespace Ponticlaro\Bebop\UI\Plugins\ContentList;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\UI\Helpers\ModuleFactory;
use Ponticlaro\Bebop\UI\Module;
use Ponticlaro\Bebop\UI\Plugins\Media\Media;
use Ponticlaro\Bebop\ScriptsLoader\Css;
use Ponticlaro\Bebop\ScriptsLoader\Js;

class ContentList extends \Ponticlaro\Bebop\UI\Patterns\PluginAbstract {

  /**
   * Identifier Key to call this plugin
   * 
   * @var string
   */
  protected static $__key = 'List';

  /**
   * Flags if templates were already rendered or not
   * 
   * @var boolean
   */
  protected $templates_rendered = false;

  /**
   * Instantiates this class
   * 
   */
  public function __construct()
  {
    if ($args = func_get_args()) 
      call_user_func_array(array($this, '__createInstance'), $args);
  }

  /**
   * Creates instance of this class
   * 
   * @param  string $key    Content list title
   * @param  array  $data   Data list
   * @param  array  $config Configuration
   * @return object         This class instance
   */
  protected function __createInstance($key, $data = array(), array $config = array())
  { 
    $title = $key;
    $key   = Utils::slugify($key);

    // Default main configuration
    $default_config = array(
      'key'              => $key,
      'title'            => $title,
      'description'      => '',
      'field_name'       => $key,
      'show_top_form'    => true,
      'show_bottom_form' => true,
      'type'             => 'single',
      'mode'             => 'default',
      'file_upload'      => [
        'name'   => 'id',
        'config' => [
          'modal_title'           => 'Upload or select existing images',
          'modal_button_text'     => 'Add Images',
          'modal_select_multiple' => true,
          'mime_types'            => [
            'image'
          ]
        ]
      ],
      'labels' => array(
        'add_button'      => 'Add Item',
        'sort_button'     => 'Sort',
        'edit_all_button' => 'Edit All'
      ),
      'no_items_message' => 'No items added until now'
    );

    // Main configuration
    $this->config = new Collection(array_replace_recursive($default_config, $config));

    // Data
    $this->data = new Collection($data ?: array());

    // Views
    $this->views_sections = (new Collection(array(
      'browse'  => [],
      'reorder' => [],
      'edit'    => []
    )))->disableDottedNotation();

    $this->views = (new Collection(array(
      'browse'  => '',
      'reorder' => '',
      'edit'    => ''
    )))->disableDottedNotation();

    // Labels
    $this->labels = (new Collection($this->config->get('labels')))->disableDottedNotation();

    // Forms
    $this->forms = (new Collection())->disableDottedNotation();

    // Add default form
    // This is inherited by user added forms without form elements
    $this->addForm('default', array(
      'add'      => __DIR__ .'/views/partials/form/default/elements/add.php',
      'sort'     => __DIR__ .'/views/partials/form/default/elements/sort.php',
      'edit_all' => __DIR__ .'/views/partials/form/default/elements/edit_all.php',
    ));

    // Add main form
    $this->addForm('main', array(
      'add'      => __DIR__ .'/views/partials/form/default/elements/add.php',
      'sort'     => __DIR__ .'/views/partials/form/default/elements/sort.php',
      'edit_all' => __DIR__ .'/views/partials/form/default/elements/edit_all.php',
    ));

    // Register templates on admin footer
    add_action('admin_footer', array($this, 'renderTemplates'));

    return $this;
  }

  /**
   * This function will register everything on the right hooks
   * when the plugin is added to Bebop::UI
   *  
   * @return void
   */
  public function load()
  {
    ///////////////
    // IMPORTANT //
    ///////////////
    // We should always enqueue scripts to make sure all possible 
    // implementations of AdminPages, Metaboxes and UI ContentList work properly
    add_action('init', [$this, 'registerScripts']);
    add_action('init', [$this, 'enqueueScripts']);
  }

  /**
   * Registers stylesheets and scripts
   * 
   * @return void
   */
  public function registerScripts()
  {
    $base_url = UrlManager::getInstance()->get('_bebop/static/ui');
    $css      = Css::getInstance()->getHook('back');
    $js       = Js::getInstance()->getHook('back');

    // Register CSS
    $css->register('bebop-ui--list', $base_url .'/list/css/bebop-ui--list', ['bebop-ui', 'bebop-ui--media']);

    // Register development JS
    if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {
      
      $js->register('bebop-ui--listView', $base_url .'/list/js/views/List');
      $js->register('bebop-ui--listItemView', $base_url .'/list/js/views/ListItemView');
      $js->register('bebop-ui--listItemModel', $base_url .'/list/js/models/ListItemModel');
      $js->register('bebop-ui--listCollection', $base_url .'/list/js/collections/ListCollection');
      $js->register('bebop-ui--list', $base_url .'/list/js/bebop-ui--list', [
        'jquery',
        'jquery-ui-sortable',
        'underscore',
        'backbone',
        'mustache',
        'bebop-ui--media',
        'bebop-ui--listView',
        'bebop-ui--listItemView',
        'bebop-ui--listItemModel',
        'bebop-ui--listCollection',
      ]);
    }

    // Register optimized JS
    else {

      // The following dependencies should never be concatenated and minified
      // Some are use by other WordPress features and plugins
      // and other are register by Bebop UI
      $js->register('bebop-ui--list', $base_url .'/list/js/bebop-ui--list.min', [
        'jquery',
        'jquery-ui-sortable',
        'underscore',
        'backbone',
        'mustache',
        'bebop-ui--media'
      ]);
    }
  }

  /**
   * Enqueues stylesheets and scripts
   * 
   * @return void
   */
  public function enqueueScripts()
  {
    global $wp_version;

    if (version_compare($wp_version, '4.0', '>=')) {
      
      wp_enqueue_media();
    }

    elseif (version_compare($wp_version, '3.5', '>=')) {
      
      // Enqueue media scripts ONLY if needed
      add_action('admin_enqueue_scripts', function() {

        if (!did_action('wp_enqueue_media'))
          wp_enqueue_media();
      }); 
    }

    else {
      // Handle WordPress lower than 3.5
    }

    Css::getInstance()->getHook('back')->enqueue('bebop-ui--list');
    Js::getInstance()->getHook('back')->enqueue('bebop-ui--list');
  }

  /**
   * Renders templates
   * 
   * @return void
   */
  public function renderTemplates()
  { 
    if (!$this->templates_rendered) { ?>

      <div id="bebop-list--<?php echo $this->getFieldName(); ?>-templates-container">
        <script bebop-list--itemTemplate="main" class="bebop-list--item" type="text/template" style="display:none">

          <input bebop-list--el="data-container" type="hidden">
          
          <div class="bebop-list--drag-handle">
            <span class="bebop-ui-icon-move"></span>
          </div>
          
          <div bebop-list--el="content" class="bebop-ui-clrfix">
            <div bebop-list--view="browse"></div>
            <div bebop-list--view="reorder"></div>
            <div bebop-list--view="edit"></div>
          </div>

          <div bebop-list--el="item-actions">
            <button bebop-list--action="edit" class="button button-small">
              <b>Edit</b>
              <span class="bebop-ui-icon-edit"></span>
            </button>
            <button bebop-list--action="remove" class="button button-small">
              <span class="bebop-ui-icon-remove"></span>
            </button>
          </div>
        </script>

        <?php 

        // Form Templates
        $forms = $this->getAllForms();

        if ($forms) {
          foreach ($forms as $form) { ?>
             
            <script bebop-list--formTemplate="<?php echo $form->getId(); ?>" type="text/template" style="display:none">
              <?php echo $this->getFormHtml($form->getId()); ?>
            </script>

          <?php }
        }

        // Item Templates
        $items_views = $this->getAllItemViews();

        if ($items_views) {
          foreach ($items_views as $key => $template) { ?>
             
            <script bebop-list--itemTemplate="<?php echo $key; ?>" type="text/template" style="display:none"><?php echo $this->getHtml($template); ?></script>

          <?php }
        } ?>
      </div>

      <?php

      $this->templates_rendered = true;
    }
  }

  /**
   * Sets the value for the target config key
   * 
   * @param  string $key   Config key
   * @param  mixed  $value Config value
   * @return object        This classobject
   */
  public function setConfig($key, $value)
  {
    if (is_string($key)) 
      $this->config->set($key, $value);

    return $this;
  }

  /**
   * Returns the target config value
   * 
   * @return mixed Config value
   */
  public function getConfig($key)
  {
    return is_string($key) ? $this->config->get($key) : null;
  }

  /**
   * Sets a list of data
   * 
   * @param array $data [description]
   */
  public function setData(array $data = array())
  {
    $this->data->set($data);

    return $this;
  }

  /**
   * Returns all data
   * 
   * @return array List of all data
   */
  public function getData()
  {
    return $this->data->getAll();
  }

  /**
   * Sets title
   * 
   * @param string $title Content list title
   */
  public function setTitle($title)
  {
    if (is_string($title)) 
      $this->config->set('title', $title);

    return $this;
  }

  /**
   * Returns title
   * 
   * @return string Content list title
   */
  public function getTitle()
  {
    return $this->config->get('title');
  }

  /**
   * Sets description
   * 
   * @param string $description Description
   */
  public function setDescription($description)
  {
    if (is_string($description)) 
      $this->config->set('description', $description);

    return $this;
  }

  /**
   * Returns description
   * 
   * @param string Description
   */
  public function getDescription()
  {
    return $this->config->get('description');
  }

  /**
   * Sets field name
   * 
   * @param string $name Field name
   */
  public function setFieldName($name)
  {
    if (is_string($name)) 
      $this->config->set('field_name', $name);

    return $this;
  }

  /**
   * Returns field name
   * 
   * @return string Field name
   */
  public function getFieldName()
  {
    return $this->config->get('field_name');
  }

  /**
   * Sets a single label
   * 
   * @param string $key   Label config id
   * @param string $value Label value
   */
  public function setLabel($key, $value)
  { 
    if (is_string($key) && is_string($value)) 
      $this->labels->set($key, $value);

    return $this;
  }

  /**
   * Returns a single label
   * 
   * @param  string $key Label config id
   * @return string      Label value
   */
  public function getLabel($key)
  { 
    if (!is_string($key)) return '';

    return $this->labels->get($key);
  }

  /**
   * Returns all labels
   * 
   * @return array All labels
   */
  public function getAllLabels()
  { 
    return $this->labels->getAll();
  }

  /**
   * Sets configuration mode
   * 
   * @param string $mode Configuration mode ID
   */
  public function setMode($mode)
  { 
    if (is_string($mode)) {

      $this->config->set('mode', $mode);

      if ($mode == 'gallery')
        $this->labels->set('add_button', 'Add images');
    }

    return $this;
  }

  /**
   * Returns current configuration mode
   * 
   * @return string Configuration mode ID
   */
  public function getMode()
  { 
    return $this->config->get('mode');
  }

  /**
   * Checks is the target configuration mode is active
   * 
   * @param  string  $mode Configuration mode ID
   * @return boolean       True if active, false otherwise
   */
  public function isMode($mode)
  {
    return is_string($mode) && $this->config->get('mode') == $mode ? true : false;
  }

  /**
   * Adds form 
   * 
   * @param string $id       Form ID
   * @param array  $elements Form ID
   */
  public function addForm($id, array $elements = array())
  {
    $this->forms->set($id, new ContentListForm($id, $elements ?: $this->forms->get('default')->getAllElements()));

    return $this;
  }

  /**
   * Sets/Replaces several form elements at the same time
   * 
   * @param string $form_id  Form ID
   * @param array  $elements Form ID
   */
  public function setFormElements($form_id, array $elements)
  {
    foreach ($elements as $element_id => $template) {
      
      $this->setFormElement($form_id, $element_id, $template);
    }

    return $this;
  }

  /**
   * Sets/Replaces a single form element
   * 
   * @param string $form_id    Form ID
   * @param string $element_id Form element ID
   * @param string $template   Form element template
   */
  public function setFormElement($form_id, $element_id, $template)
  {
    if (!is_string($form_id) || !is_string($element_id))
      throw new \Exception("Form ID and Element ID must both be strings");

    // Add form if it does not exist
    if (!$this->forms->hasKey($form_id))
      $this->addForm($form_id);

    // Add element to form
    $this->forms->get($form_id)->addElement($element_id, $template);

    return $this;
  }

  /**
   * Adds form elements
   * 
   * @param string $form_id  Form ID
   * @param array  $elements Form elements
   */
  public function addFormElements($form_id, array $elements)
  {
    foreach ($elements as $element_id => $template) {
      $this->addFormElement($form_id, $element_id, $template);
    }

    return $this;
  }

  /**
   * Adds a single form element
   * 
   * @param string $form_id    Form ID
   * @param string $element_id Form element ID
   * @param string $template   Form element template
   */
  public function addFormElement($form_id, $element_id, $template)
  {
    if (!is_string($form_id) || !is_string($element_id))
      throw new \Exception("Form ID and Element ID must both be strings");

    // Add form if it does not exist
    if (!$this->forms->hasKey($form_id))
      $this->addForm($form_id);

    // Add element to form
    $form = $this->forms->get($form_id);

    if ($form->hasElement($element_id))
      throw new \Exception("There is already a form element named '$element_id'. To replace it use setFormElement() instead.");
      
    $form->addElement($element_id, $template);

    return $this;
  }

  /**
   * Checks if target form element exists
   * 
   * @param  string  $form_id    Form ID
   * @param  string  $element_id Form element ID
   * @return boolean             True if exists, false otherwise
   */
  public function formHasElement($form_id, $element_id)
  {
    if (!is_string($form_id) || !is_string($element_id)) return false;

    return $this->forms->get($form_id) ? $this->forms->get($form_id)->hasElement($id) : false;
  }

  /**
   * Removes target form element
   * 
   * @param  string $form_id    Form ID
   * @param  string $element_id Form element ID
   * @return object             This class instance
   */
  public function removeFormElement($form_id, $element_id)
  {
    if (!is_string($form_id) || !is_string($element_id))
      throw new \Exception("Form ID and Element ID must both be strings");

    // Remove element from form
    if ($this->forms->hasKey($form_id))
      $this->forms->get($form_id)->removeElement($element_id);

    return $this;
  }

  /**
   * Removes all form elements
   * 
   * @param  string $form_id Form ID
   * @return object          This class instance
   */
  public function clearFormElements($form_id)
  {
    // Remove element from form
    if ($this->forms->hasKey($form_id))
      $this->forms->get($form_id)->clearElements();

    return $this;
  }

  /**
   * Returns target form elements
   * 
   * @param  string $id Form ID
   * @return array      Form elements
   */
  public function getForm($id)
  {
    if (!is_string($form_id))
      throw new \Exception("Form ID must both be a string");

    return $this->forms->get($id);
  }

  /**
   * Returns all forms
   * 
   * @return array List of all forms
   */
  public function getAllForms()
  {
    return $this->forms->getAll();
  }

  /**
   * Sets list item view
   * 
   * @param string $view     View ID
   * @param string $template View template
   */
  public function setItemView($view, $template)
  {
    if(!is_string($view)) 
      return $this;

    $this->views->set($view, $template);

    return $this;
  }

  /**
   * Returns target list item view template
   * 
   * @param  string $view View ID
   * @return string       View template
   */
  public function getItemView($view)
  {
    if(!is_string($view)) return $this;

    return $this->views->get($view);
  }

  /**
   * Returns all list item views
   * 
   * @return array List of all item views
   */
  public function getAllItemViews()
  {
    return $this->views->getAll();
  }

  /**
   * Adds multiple item view sections
   * 
   * @param string $view     View ID
   * @param array  $sections List of Bebop UI Module definitions
   */
  public function addItemViewSections($view, array $sections = [])
  {
      foreach ($sections as $args) {
        $this->addItemViewSection($view, $args);
      }

      return $this;
  }

  /**
   * Adds a single section to a item view
   * 
   * @param string $view View ID
   * @param string $id   Bebop UI Module ID
   * @param array  $args Bebop UI Module configuration
   */
  public function addItemViewSection($view, array $args)
  {
    $ui_id = isset($args['ui']) && $args['ui'] ? $args['ui'] : null;

    if (ModuleFactory::canManufacture($ui_id)) {

      unset($args['ui']);
      $section = ModuleFactory::create($ui_id, $args);

      if ($section)
        $this->views_sections->push($section, $view);
    }

    return $this;
  }

  /**
   * Returns all view sections
   * 
   * @return array List of view sections
   */
  public function getAllViewSections()
  {
    return $this->views_sections->getAll();
  }

  /**
   * Returns form HTML
   * 
   * @param  string $id Form ID
   * @return string     Resulting HTML
   */
  public function getFormHtml($id)
  {
    $html     = '';
    $elements = $this->forms->get($id)->getAllElements();

    if ($elements) {
      
      foreach ($elements as $id => $template) {
        
        $html .= "<div bebop-list--formElementId='$id' class='bebop-list--formField'>";
        
        if (is_array($template)) {
          
          foreach ($template as $section) {
              
            $ui_id = isset($section['ui']) && $section['ui'] ? $section['ui'] : null;

            unset($section['ui']);
            $section = ModuleFactory::create($ui_id, $section);

            if ($section) {
              
              ob_start();
              $section->render();
              $new_html = ob_get_contents();
              ob_end_clean();

              $html .= $new_html;
            }
          }
        }

        elseif (is_string($template)) {
          
          $html .= $this->getHtml($template); 
        }

        $html .= '</div>';
      }
    }

    return $html;
  }

  /**
   * Returns HTML from callables, files or strings
   * 
   * @param  mixed $source HTML source: callable, file path or string
   * @return string        Resulting HTML
   */
  public function getHtml($source) 
  {
    $html = '';

    if (is_callable($source)) {

      ob_start();
      call_user_func($source);
      $html = ob_get_contents();
      ob_end_clean();
    } 

    elseif (is_file($source) && is_readable($source)) {

      ob_start();
      $this->__renderTemplate($source, $this);
      $html = ob_get_contents();
      ob_end_clean();
    } 

    elseif (is_string($source)) {

      $html = $source;
    }

    return $html;
  }

  /**
   * Build item views from sections
   * 
   * @return void
   */
  protected function buildItemViewsFromSections()
  {
    $view_sections = $this->views_sections->getAll();

    foreach ($view_sections as $view => $sections) {

      // Add Gallery Mode sections
      if ($this->isMode('gallery')) {

        $file_upload_config = array_merge([
          'before' => '<div class="bebop-ui-mod bebop-ui-mod-fileupload bebop-ui-mod-fileupload-on-gallery-list">'
        ], $this->getConfig('file_upload') ?: []);

        array_unshift($sections, ModuleFactory::create('fileupload', $file_upload_config));

        // Add default reorder view for gallery mode
        if ($view == 'reorder' && count($sections) == 1) {

          $sections[] = ModuleFactory::create('rawHtml', [
            'html' => '<span class="bebop-list--media-title">{{image.title}}</span>'
          ]);
        }

        // Making sure old style gallery mode with template files still works
        if ($this->getItemView($view)) {

          $sections[] = ModuleFactory::create('rawHtml', [
            'html' => $this->getHtml($this->getItemView($view))
          ]);
        }
      }

      // Handle manually added sections
      if ($sections && is_array($sections)) {

        ob_start();

        foreach ($sections as $section) {

          $data = [];

          if ($section->getVar('name'))
            $data[$section->getVar('name')] = '{{'. $section->getVar('name') .'}}';

          // Handle checked/selected
          $el = $section->getEl();

          if ($el) {

            if (is_a($el, 'Ponticlaro\Bebop\Html\Elements\Select')) {

              $section->preRendering();

              if ($section->configIsValid()) {

                $section->renderBeforeMainTemplate();
                echo $el->getOpeningTag();

                if (is_a($section, 'Ponticlaro\Bebop\UI\Modules\PostSearch')) {
                  
                  $name = $el->getName();

                  echo "{{#${name}}}<option selected value='{{.}}'>{{.}}</option>{{/${name}}}";
                }

                else {

                  $opt_elements = $el->getOptionsAsElements();

                  if ($opt_elements) {
                    foreach ($opt_elements as $opt_el) {

                      $name  = $opt_el->getName();
                      $value = $opt_el->getValue();

                      $opt_el->removeAttr('selected');

                      // Set 'selected' attribute for Mustache
                      if ($el->allowsMultipleValues()) {
        
                        $opt_el->setAttr("{{#${name}_has_${value}}}selected{{/${name}_has_${value}}}");
                       }

                      else {

                        $opt_el->setAttr("{{#${name}_is_${value}}}selected{{/${name}_is_${value}}}");
                      }

                      $opt_el->render();
                    }
                  }
                }

                echo $el->getClosingTag();
                $section->renderAfterMainTemplate();
              }
            }

            elseif (is_a($el, 'Ponticlaro\Bebop\Html\Elements\Checkbox') || is_a($el, 'Ponticlaro\Bebop\Html\Elements\Radio')) {
              
              $section->preRendering();

              if ($section->configIsValid()) {

                $section->renderBeforeMainTemplate();

                $elements = $el->getOptionsAsElements();

                if ($elements) {
                  foreach ($elements as $opt_el) {

                    $name  = $opt_el->getName();
                    $value = $opt_el->getValue();

                    $opt_el->removeAttr('checked');

                    // Set 'checked' attribute for Mustache
                    if ($opt_el->allowsMultipleValues()) {
      
                      $opt_el->setAttr("{{#${name}_has_${value}}}checked{{/${name}_has_${value}}}");
                     }

                    else {

                      $opt_el->setAttr("{{#${name}_is_${value}}}checked{{/${name}_is_${value}}}");
                    }

                    $opt_el->render();
                  }
                }

                $section->renderAfterMainTemplate();
              }
            }

            elseif (is_a($el, 'Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList')) {
              
              $section->preRendering();

              if ($section->configIsValid()) {

                $el->setConfig('data', '{{'. $el->getFieldName() .'}}');
                $el->setConfig('childList', true);
                $section->renderBeforeMainTemplate();
                $section->renderMainTemplate();
                $section->renderAfterMainTemplate();
              }
            }

            elseif (is_a($el, 'Ponticlaro\Bebop\UI\Plugins\Media\Media')) {
              
              $section->preRendering();

              if ($section->configIsValid()) {

                $el->setConfig('data', '{{'. $el->getName() .'}}');
                $section->renderBeforeMainTemplate();
                $section->renderMainTemplate();
                $section->renderAfterMainTemplate();
              }
            }

            elseif (is_a($el, 'Ponticlaro\Bebop\Html\ControlElement')) {

              $el->setValue('{{'. $section->getVar('name') .'}}');
              $section->setEl($el)->render($data);
            }
          }

          else {

            $section->render($data);
          }
        }

        $html = ob_get_contents();
        ob_end_clean();
        
        $this->setItemView($view, $html);
      }
    }
  }

  /**
   * Renders content list
   * 
   * @return object This class instance
   */
  public function render()
  {
    $this->buildItemViewsFromSections();

    // Render list
    $this->__renderTemplate('default', $this);

    return $this;
  }

  /**
   * Renders template
   * 
   * @param  string $template_name Template to be rendered
   * @param  object $instance      This class instance
   * @return void
   */
  protected function __renderTemplate($template_name, $instance)
  {
    // Absolute path templates
    if (is_file($template_name) && is_readable($template_name)) {
      
      include $template_name;
    }

    // Main View Templates
    else {

      include __DIR__ . '/views/'. $template_name .'.php';
    }
  }

  //////////////////////////////////////////////
  // START OF OLD API                         //
  // Will be deprecated on next major version //
  //////////////////////////////////////////////
  public function clearForm()
  {
    $this->forms->get('main')->clearElements();

    return $this;
  }

  public function addFormEl($element_id, $template)
  {
    $this->forms->get('main')->addElement($element_id, $template);

    return $this;
  }

  public function prependFormEl($element_id, $template)
  {
    $this->forms->get('main')->addElement($element_id, $template);

    return $this;
  }

  public function appendFormEl($element_id, $template)
  {
    $this->forms->get('main')->addElement($element_id, $template);

    return $this;
  }

  public function replaceFormEl($element_id, $template)
  {
    $this->forms->get('main')->addElement($element_id, $template);

    return $this;
  }

  public function removeFormEl($element_id)
  {
    $this->forms->get('main')->removeElement($element_id);

    return $this;
  }

  public function showForms($value)
  {
    $this->showTopForm($value);
    $this->showBottomForm($value);

    return $this;
  }

  public function showTopForm($value)
  {
    if (is_bool($value))
      $this->config->set('show_top_form', $value);

    return $this;
  }

  public function showBottomForm($value)
  {
    if (is_bool($value))
      $this->config->set('show_bottom_form', $value);

    return $this;
  }
  //////////////////////////////////////////////
  // END OF OLD API                           //
  // Will be deprecated on next major version //
  //////////////////////////////////////////////
}