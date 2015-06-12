<?php

namespace Ponticlaro\Bebop\UI\Plugins\Media;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\PathManager;
use Ponticlaro\Bebop\Common\Utils;

class Media extends \Ponticlaro\Bebop\UI\Patterns\PluginAbstract {

	/**
	 * Identifier Key to call this plugin
	 * 
	 * @var string
	 */
	protected static $__key = 'Media';

	protected static $__base_url;

	protected $__instances;

	protected $__current_instance_key;

	public function __construct()
	{
		self::$__base_url = Utils::getPathUrl(__DIR__);

		$this->__instances = new Collection();

		$args = func_get_args();

		if ($args) call_user_func_array(array($this, '__createInstance'), $args);
	}

	private function __createInstance($key, $data = null, array $config = array())
	{	
		$this->__enqueueScripts();

		$label = $key;
		$key   = Utils::slugify($key);

		$default_config = array(
			'key'                  => $key,
			'field_name'           => $key,
			'data'                 => $data,
			'select_button_class'  => '',
			'select_button_text'   => 'Select '. $label,
			'remove_button_class'  => '',
			'remove_button_text'   => 'Remove '. $label,
			'no_selection_message' => 'No selected item',  
			'modal_title'          => 'Upload or select existing resources',
			'modal_button_text'    => 'Select '. $label,
			'mime_types'           => array()
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
		add_action('admin_enqueue_scripts', array($this, 'registerScripts'));
		add_action('admin_footer', array($this, 'renderTemplates'));
	}

	public function registerScripts()
	{
		$paths = PathManager::getInstance();
		$urls  = UrlManager::getInstance();

		// Register CSS
		$css_path         = 'media/css/bebop-ui--media';
		$css_version      = Utils::getFileVersion($paths->get('_bebop/static/ui', $css_path .'.css'));
		$css_dependencies = array('bebop-ui');

		wp_register_style('bebop-ui--media', $urls->get('_bebop/static/ui', $css_path), $css_dependencies, $css_version);
		
		// Register development JS
		if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {
			
			wp_register_script('bebop-ui--mediaView', $urls->get('_bebop/static/ui', 'media/js/views/Media'), array(), false, true);

			$js_dependencies = array(
				'jquery',
				'jquery-ui-sortable',
				'underscore',
				'backbone',
				'bebop-ui',
				'mustache',
				'bebop-ui--mediaView'
			);		

			wp_register_script('bebop-ui--media', $urls->get('_bebop/static/ui', 'media/js/bebop-ui--media'), $js_dependencies, false, true);
		}

		// Register optimized JS
		else {

			// The following dependencies should never be concatenated and minified
			// Some are use by other WordPress features and plugins
			// and other are register by Bebop UI
			$js_dependencies = array(
				'jquery',
				'jquery-ui-sortable',
				'underscore',
				'backbone',
				'bebop-ui',
				'mustache'
			);

			$js_path    = 'media/js/bebop-ui--media.min';
			$js_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $js_path .'.js'));

			wp_register_script('bebop-ui--media', $urls->get('_bebop/static/ui', $js_path), $js_dependencies, $js_version, true);
		}
	}

	public function renderTemplates()
	{
		?>
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
				<div class="bebop-media--previewer-icon bebop-ui-icon-file"></div>
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

	private function __enqueueScripts()
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

	private function __renderTemplate($template_name, $data)
	{
		include __DIR__ . '/templates/'. $template_name .'.php';
	}

	public function render()
	{
		$this->__renderTemplate($this->template, $this->config);

		return $this;
	}
}