<?php

namespace Ponticlaro\Bebop;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\PathManager;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\Common\StaticAssetsServer;
use Ponticlaro\Bebop\HttpApi;

class UI extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

	/**
	 * Class that plugins should be extending to get loaded
	 * 
	 */
	const PLUGIN_ABSTRACT_CLASS = 'Ponticlaro\Bebop\UI\Patterns\PluginAbstract';

	/**
	 * URL for current directory
	 * @var string
	 */
	private static $__base_url;

	/**
	 * List of plugins available
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection;
	 */
	private $__plugins;

	/**
	 * Instantiates UI object
	 * 
	 */
	protected function __construct()
	{
		// Get URL for current directory
		self::$__base_url = Utils::getPathUrl(__DIR__);

		// Instantiate plugins collection object
		$this->__plugins = new Collection();

		// Add built-in plugins
		$this->addPlugins(array(
			'Ponticlaro\Bebop\UI\Plugins\Media\Media',
			'Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList',
			'Ponticlaro\Bebop\UI\Plugins\MultiContentList\MultiContentList',
			'Ponticlaro\Bebop\UI\Plugins\Gallery\Gallery'
		));

		// Register common UI scripts
		add_action('admin_enqueue_scripts', array($this, 'registerScripts'));

		// Set assets directory
		$assets_dir = __DIR__ . '/UI/assets';

		// Set static assets URL
        $paths = PathManager::getInstance(); 
		$paths->set('_bebop/static/ui', $assets_dir);

		// Set static assets directory
        $urls = UrlManager::getInstance(); 
        $urls->set('_bebop/static/ui', $urls->get('home', '_bebop/static/ui'));

		// Setup static assets server
		$http_api = new HttpApi('bebop_ui_static_assets', '_bebop/static/ui');
	    new StaticAssetsServer($http_api, $assets_dir);
	}

	/**
	 * Register common scripts for UI plugins
	 * 
	 * @return void
	 */
	public function registerScripts()
	{
		$paths = PathManager::getInstance();
		$urls  = UrlManager::getInstance();
		
		// Register CSS
		$css_path    = 'core/css/bebop-ui';
		$css_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $css_path .'.css'));

		wp_register_style('bebop-ui', $urls->get('_bebop/static/ui', $css_path), array(), $css_version);

		// Register development JS
		if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {
			
			wp_register_script('mustache', $urls->get('_bebop/static/ui', 'core/js/vendor/mustache'), array(), '0.8.1', true);
			wp_register_script('jquery.debounce', $urls->get('_bebop/static/ui', 'core/js/vendor/jquery.ba-throttle-debounce.min'), array('jquery'), '0.8.1', true);
			
			$dependencies = array(
				'jquery',
				'jquery-ui-datepicker',
				'jquery.debounce'
			);
			
			wp_register_script('bebop-ui', $urls->get('_bebop/static/ui', 'core/js/bebop-ui'), $dependencies, false, true);
		}

		// Register optimized JS
		else {

			// Mustache is optimized separately 
			// so that other components can load it only if needed
			$mustache_path    = 'core/js/vendor/mustache.min';
			$mustache_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $mustache_path .'.js')); 
			
			wp_register_script('mustache', $urls->get('_bebop/static/ui', $mustache_path), array(), $mustache_version, true);

			// The following dependencies should never be concatenated and minified
			// These are used by other WordPress features and plugins
			$dependencies = array(
				'jquery',
				'jquery-ui-datepicker'
			);

			$bebop_ui_path    = 'core/js/bebop-ui.min';
			$bebop_ui_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $bebop_ui_path .'.js')); 

			wp_register_script('bebop-ui', $urls->get('_bebop/static/ui', $bebop_ui_path), $dependencies, $bebop_ui_version, true);
		}
	}

	/**
	 * Adds single plugin class
	 * 
	 * @param string $plugin Class containing a plugin
	 */
	public function addPlugin($plugin)
	{
		$this->__addPlugin($plugin);

		return $this;
	}

	/**
	 * Adds a batch of plugins
	 * 
	 * @param array $plugins Array with plugin classes
	 */
	public function addPlugins(array $plugins = array())
	{	
		foreach ($plugins as $plugin) {
			
			$this->__addPlugin($plugin);
		}

		return $this;
	}

	/**
	 * Internal function to handle addition of a single plugin
	 * 
	 * @param  string $plugin Plugin class
	 * @return void
	 */
	private function __addPlugin($plugin)
	{
		if (is_string($plugin) && class_exists($plugin)) {

			$class = new \ReflectionClass($plugin);

			if ($class->isSubclassOf(self::PLUGIN_ABSTRACT_CLASS)) {
				
				// Load plugin
				$instance = $class->newInstance();
				$instance->load();

				// Store referenc to plugin class
				$this->__plugins->set($plugin::getKey(), $plugin);
			}
		}
	}

	/**
	 * Calls the target plugin using its key
	 * 
	 * @param  string $name Key that identifies the target plugin
	 * @param  array  $args Arguments to pass to target plugin
	 * @return mixed        New instance of target plugin class
	 */
	public function __call($name, $args)
	{
		$className = $this->__plugins->get($name);

		if (class_exists($className)) {

	        return call_user_func_array(

	        	array(
					new \ReflectionClass($className), 
					'newInstance'
				), 
	            $args
	        );
	    }
	}
}