<?php

namespace Ponticlaro\Bebop\UI\Plugins\MultiContentList;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\PathManager;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\ScriptsLoader\Css;
use Ponticlaro\Bebop\ScriptsLoader\Js;

class MultiContentList extends \Ponticlaro\Bebop\UI\Patterns\PluginAbstract {

  /**
   * Identifier Key to call this plugin
   * 
   * @var string
   */
  protected static $__key = 'MultiList';

  /**
   * Holds configuration values
   * 
   * @var Ponticlaro\Bebop\Common\Collection
   */
  protected $__config;

  /**
   * Holds all added lists
   * 
   * @var Ponticlaro\Bebop\Common\Collection
   */
  protected $__lists;

  /**
   * Loads plugin OR creates single instance of the MultiContentList plugin
   * 
   */
  public function __construct()
  {
    // Instantiate configuration collections
    $this->__config = new Collection();
    $this->__lists  = new Collection();

    // Conditionally creates single instance of the MultiContentList plugin
    if ($args = func_get_args()) 
      call_user_func_array(array($this, '__createInstance'), $args);
  }

  /**
   * This function will register everything on the right hooks
   * when the plugin is added to Bebop::UI
   *  
   * @return void
   */
  public function load()
  {
    // Register back-end scripts
    add_action('init', array($this, 'registerScripts'));
    add_action('init', array($this, 'enqueueScripts'));
  }

  /**
   * Register MultiContentList scripts
   */
  public function registerScripts()
  {
    $base_url = UrlManager::getInstance()->get('_bebop/static/ui');
    $css      = Css::getInstance()->getHook('back');
    $js       = Js::getInstance()->getHook('back');

    // Register CSS
    $css->register('bebop-ui--multilist', $base_url .'/multilist/css/bebop-ui--multilist', ['bebop-ui']);

    // Register development JS
    if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {
      
      $js->register('bebop-ui--multilistView', $base_url .'/multilist/js/views/MultiList');
      $js->register('bebop-ui--multilist', $base_url .'/multilist/js/bebop-ui--multilist', [
        'jquery',
        'jquery-ui-tabs',
        'bebop-ui--multilistView'
      ]);
    }

    // Register optimized JS
    else {

      // The following dependencies should never be concatenated and minified
      // Some are use by other WordPress features and plugins
      // and other are register by Bebop UI
      $js->register('bebop-ui--multilist', $base_url .'/multilist/js/bebop-ui--multilist.min', [
        'jquery',
        'jquery-ui-tabs'
      ]);
    }
  }

  /**
   * Enqueues scripts that MultiContentList needs
   * 
   */
  public function enqueueScripts()
  {
    wp_enqueue_style('bebop-ui--multilist');
    wp_enqueue_script('bebop-ui--multilist');
  }

  /**
   * Creates single instance of the MultiContentList plugin
   * 
   * @param  string $title  Instance Title. Also used to create a slugified key
   * @param  array  $config Configuration array
   * @return object         Ponticlaro\Bebop\UI\Plugins\MultiContentList
   */
  private function __createInstance($title, array $config = array())
  { 
    // Enqueue all scripts that the MultiContentList needs
    $this->__enqueueScripts();

    // Create slugified $key from $title
    $key = Utils::slugify($title);

    // Set default configuration values
    $this->__config->set(array(
      'key'   => $key,
      'title' => $title,
      'mode'  => 'default'
    ));

    // Set configuration values from input
    $this->__config->set($config);

    return $this;
  }

  /**
   * Adds a single list
   * 
   * @param \Ponticlaro\Bebop\UI\Plugins\ContentList $list  ContentList instance
   */
  public function addList(\Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList $list, array $data = array())
  {
    // Override list data
    if ($data) 
      $list->setData($data);

    // Store list
    $this->__lists->push($list);

    return $this;
  }

  /**
   * Calls the internal renders function
   * 
   * @return object Ponticlaro\Bebop\UI\Plugins\MultiContentList
   */
  public function render()
  {
    $this->__renderTemplate($this->__lists);

    return $this;
  }

  /**
   * Renders template and lists
   * 
   * @param  \Ponticlaro\Bebop\Common\Collection $lists Lists collection
   * @return void
   */
  private function __renderTemplate(\Ponticlaro\Bebop\Common\Collection $lists)
  {
    include __DIR__ . '/templates/main.php';
  }
}