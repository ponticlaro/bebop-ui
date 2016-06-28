<?php

namespace Ponticlaro\Bebop\UI\Plugins\Media;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\Html;
use Ponticlaro\Bebop\ScriptsLoader\Css;
use Ponticlaro\Bebop\ScriptsLoader\Js;

class Media extends \Ponticlaro\Bebop\UI\Patterns\PluginAbstract {

  /**
   * Identifier Key to call this plugin
   * 
   * @var string
   */
  protected static $__key = 'Media';
  
  /**
   * Container DOM element
   * 
   * @var Ponticlaro\Bebop\Html\Element
   */
  protected $el;

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
   * @param  string $key    Media title
   * @param  string $data   Data
   * @param  array  $config Configuration
   * @return object         This class instance
   */
  protected function __createInstance($key, $data = null, array $config = array())
  { 
    $label = $key;
    $key   = Utils::slugify($key);

    $this->el = Html::Div();

    $default_config = array(
      'key'                   => $key,
      'field_name'            => $key,
      'attrs'                 => [],
      'data'                  => $data,
      'select_button_class'   => '',
      'select_button_text'    => 'Select '. $label,
      'remove_button_class'   => '',
      'remove_button_text'    => 'Remove '. $label,
      'no_selection_message'  => 'No selected item',  
      'modal_select_multiple' => false,
      'modal_title'           => 'Upload or select existing resources',
      'modal_button_text'     => 'Select '. $label,
      'mime_types'            => array()
    );

    $this->config   = new Collection(array_merge($default_config, $config));
    $this->template = 'single';

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
    add_action('admin_init', array($this, 'registerScripts'));
    add_action('admin_init', array($this, 'enqueueScripts'));
    add_action('admin_footer', array($this, 'renderTemplates'));
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
    $css->register('bebop-ui--media', $base_url .'/media/css/bebop-ui--media', ['bebop-ui']);

    // Register development JS
    if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {

      $js->register('bebop-ui--mediaView', $base_url .'/media/js/views/Media');
      $js->register('bebop-ui--media', $base_url .'/media/js/bebop-ui--media', [
        'jquery',
        'jquery-ui-sortable',
        'underscore',
        'backbone',
        'bebop-ui',
        'mustache',
        'bebop-ui--mediaView'
      ]);
    }

    // Register optimized JS
    else {

      // The following dependencies should never be concatenated and minified
      // Some are use by other WordPress features and plugins
      // and other are register by Bebop UI
      $js->register('bebop-ui--media', $base_url .'/media/js/bebop-ui--media.min', [
        'jquery',
        'jquery-ui-sortable',
        'underscore',
        'backbone',
        'bebop-ui',
        'mustache'
      ]);
    }
  }

  /**
   * Renders templates
   * 
   * @return void
   */
  public function renderTemplates()
  { ?>

    <script bebop-media--template="main" type="text/template" style="display:none">
      <div bebop-media--el="previewer"></div>
      
      <div bebop-media--el="actions">
        <button bebop-media--action="select" class="button button-small">
          <b>Select</b> <span class="bebop-ui-icon-file-upload"></span>
        </button>
        <button bebop-media--action="remove" class="button button-small">
          <span class="bebop-ui-icon-remove"></span>
        </button>
      </div>
    </script>
    
    <script bebop-media--template="image-view" type="text/template" style="display:none">
      <div class="bebop-media--previewer-image">
        <div class="bebop-media--previewer-image-inner">
          <img src="{{sizes.thumbnail.url}}">
        </div>
      </div>
    </script>

    <script bebop-media--template="non-image-view" type="text/template" style="display:none">
      <div class="bebop-media--previewer-inner">
        <div class="bebop-media--previewer-icon bebop-ui-icon-{{bebop_file_icon}}"></div>
        <div class="bebop-media--previewer-file-title">{{title}}</div>
        <div class="bebop-media--previewer-info">
          <a href="{{url}}" target="_blank">Open file in new tab</a> <span class="bebop-ui-icon-share"></span>
        </div>
      </div>
    </script>

    <script bebop-media--template="empty-view" type="text/template" style="display:none">
      <div bebop-media--action="select" title="Click to select media" class="bebop-media--previewer-inner">
        <div class="bebop-media--previewer-icon bebop-ui-icon-file-remove"></div>
        <div class="bebop-media--previewer-file-title">No file selected</div>
      </div>
    </script>

    <script bebop-media--template="error-view" type="text/template" style="display:none">
      <div class="bebop-media--previewer-inner bebop-media--status-warning">
        <div class="bebop-media--previewer-icon bebop-ui-icon-warning"></div>
        <div class="bebop-media--previewer-status-code">{{status}}</div>
        <div class="bebop-media--previewer-file-title">{{message}}</div>
      </div>
    </script>

    <script bebop-media--template="loading-view" type="text/template" style="display:none">
      <div class="bebop-media--previewer-inner">
        <div class="bebop-media--previewer-icon bebop-ui-icon-busy"></div>
        <div class="bebop-media--previewer-file-title">Loading...</div>
      </div>
    </script>

  <?php }

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

    wp_enqueue_style('bebop-ui--media');
    wp_enqueue_script('bebop-ui--media');
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
   * Sets the field name
   * 
   * @param  mixed  $name Field name value
   * @return object       This classobject
   */
  public function setName($name)
  {
    if (is_string($name)) 
      $this->config->set('field_name', Utils::slugify($name));

    return $this;
  }

  /**
   * Returns the target config value
   * 
   * @return mixed Config value
   */
  public function getName()
  {
    return $this->config->get('field_name');
  }

  /**
   * Renders templates
   * 
   * @return void
   */
  protected function __renderTemplate($template_name, $data)
  {
    include __DIR__ . '/templates/'. $template_name .'.php';
  }

  /**
   * Renders content list
   * 
   * @return object This class instance
   */
  public function render()
  {
    // Set custom attributes
    $this->el->setAttrs($this->config->get('attrs'));

    // Remove custom attributes from config
    $this->config->remove('attrs');

    // Set container attributes
    $this->el->setAttr('bebop-media--el', 'container');
    $this->el->setAttr('bebop-media--config', htmlentities(json_encode($this->config->getAll())));

    // Append input element
    $input = Html::Input();
    $input->setAttr('type', 'hidden');
    $input->setAttr('name', $this->config->get('field_name'));
    $input->setAttr('value', $this->config->get('data'));
    $this->el->prepend($input);

    // Render
    $this->el->render();

    return $this;
  }
}