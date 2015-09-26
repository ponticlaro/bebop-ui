<?php

namespace Ponticlaro\Bebop\UI\Plugins\Gallery;

use Ponticlaro\Bebop\Common\Collection;
use Ponticlaro\Bebop\Common\UrlManager;
use Ponticlaro\Bebop\Common\PathManager;
use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;

class Gallery extends \Ponticlaro\Bebop\UI\Patterns\PluginAbstract {

	/**
	 * Identifier Key to call this plugin
	 * 
	 * @var string
	 */
	protected static $__key = 'Gallery';

	/**
	 * Holds configuration values
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection
	 */
	protected $config;

	/**
	 * Holds the content list
	 * 
	 * @var Ponticlaro\Bebop\UI\Plugins\ContentList
	 */
	protected $content_list;

	/**
	 * Holds media types
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection
	 */
	protected $media_types;

	/**
	 * List of enabled media types
	 * 
	 * @var Ponticlaro\Bebop\Common\Collection
	 */
	protected $enabled_media_types;

	/**
	 * Loads plugin OR creates single instance of the Gallery plugin
	 * 
	 */
	public function __construct()
	{
		// Instantiate configuration collections
		$this->config              = new Collection();
		$this->media_types         = new Collection();
		$this->enabled_media_types = new Collection(['image']);

		// Get function arguments
		$args = func_get_args();

		// Conditionally creates single instance of the Gallery plugin
		if ($args) call_user_func_array(array($this, '__createInstance'), $args);
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
		add_action('admin_enqueue_scripts', array($this, 'registerScripts'));
	}

	/**
	 * Register MultiContentList scripts
	 */
	public function registerScripts()
	{
		$paths = PathManager::getInstance();
		$urls  = UrlManager::getInstance();

		// Register CSS
		$css_path         = 'gallery/css/bebop-ui--gallery';
		$css_version      = Utils::getFileVersion($paths->get('_bebop/static/ui', $css_path .'.css'));
		$css_dependencies = array('bebop-ui');

		wp_register_style('bebop-ui--gallery', $urls->get('_bebop/static/ui', $css_path), $css_dependencies, $css_version);

		// Register development JS
		if (defined('BEBOP_DEV_ENV_ENABLED') && BEBOP_DEV_ENV_ENABLED) {
			
			$js_dependencies = array(
				'jquery'
			);		

			$js_path    = 'gallery/js/bebop-ui--gallery';
			$js_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $js_path .'.js'));

			wp_register_script('bebop-ui--gallery', $urls->get('_bebop/static/ui', $js_path), $js_dependencies, false, true);
		}

		// Register optimized JS
		else {

			// The following dependencies should never be concatenated and minified
			// Some are use by other WordPress features and plugins
			// and other are register by Bebop UI
			$js_dependencies = array(
				'jquery'
			);

			$js_path    = 'gallery/js/bebop-ui--gallery.min';
			$js_version = Utils::getFileVersion($paths->get('_bebop/static/ui', $js_path .'.js'));

			wp_register_script('bebop-ui--gallery', $urls->get('_bebop/static/ui', $js_path), $js_dependencies, $js_version, true);
		}
	}

	/**
	 * Enqueues scripts that MultiContentList needs
	 * 
	 */
	private function __enqueueScripts()
	{
		wp_enqueue_style('bebop-ui--gallery');
		wp_enqueue_script('bebop-ui--gallery');
	}

	/**
	 * Creates single instance of the Gallery plugin
	 * 
	 * @param  string $title  Instance Title. Also used to create a slugified key
	 * @param  array  $config Configuration array
	 * @return object         Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	private function __createInstance($title, $data = [], array $config = [])
	{	
		// Enqueue all scripts that the Gallery needs
		$this->__enqueueScripts();

		// Create slugified $key from $title
		$key = Utils::slugify($title);

		// Set default configuration values
		$this->config->set(array(
			'key'   => $key,
			'title' => $title
		));

		// Set configuration values from input
		$this->config->set($config);

		// Define image media type
		$this->addMediaType('image', [
			'browse'  => __DIR__ .'/templates/image-browse.php',
			'edit'    => __DIR__ .'/templates/image-edit.php',
			'reorder' => __DIR__ .'/templates/image-reorder.php',
		]);

		// Define video media type
		$this->addMediaType('video', [
			'browse'  => __DIR__ .'/templates/video-browse.mustache',
			'edit'    => __DIR__ .'/templates/video-edit.mustache',
			'reorder' => __DIR__ .'/templates/video-reorder.mustache',
		]);

		// Define audio media type
		$this->addMediaType('audio', [
			'browse'  => __DIR__ .'/templates/audio-browse.mustache',
			'edit'    => __DIR__ .'/templates/audio-edit.mustache',
			'reorder' => __DIR__ .'/templates/audio-reorder.mustache',
		]);

		// Define map media type
		$this->addMediaType('map', [
			'browse'  => __DIR__ .'/templates/map-browse.mustache',
			'edit'    => __DIR__ .'/templates/map-edit.mustache',
			'reorder' => __DIR__ .'/templates/map-reorder.mustache',
		]);

		// Configure content list
		$this->content_list = new ContentList($title, $data, $config);

		return $this;
	}

	/**
	 * Returns a single media type object
	 * 
	 * @param  string $id Media type ID
	 * @return object     Ponticlaro\Bebop\UI\Plugins\Gallery\MediaType
	 */
	public function getMediaType($id)
	{
		return $this->media_types->get($id);
	}

	/**
	 * Adds a media type
	 * 
	 * @param  string $id        Media type ID
	 * @param  array  $templates Templates array: 'browse' and 'edit' are required
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	public function addMediaType($id, array $templates)
	{
		if ($this->media_types->hasKey($id))
			throw new \Exception("Media type '$id' is already defined. Use replaceMediaType() if you want to replace the existing one");

		$media_type = new MediaType($id, $templates);

		$this->media_types->set($id, $media_type);

		return $this;
	}

	/**
	 * Replaces a media type
	 * 
	 * @param  string $id        Media type ID
	 * @param  array  $templates Templates array: 'browse' and 'edit' are required
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	public function replaceMediaType($id, array $templates)
	{
		$media_type = new MediaType($id, $templates);

		$this->media_types->set($id, $media_type);

		return $this;		
	}

	/**
	 * Removes a media type
	 * 
	 * @param  string $id Media type ID
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	public function removeMediaType($id)
	{
		$this->media_types->remove($id);

		return $this;
	}

	/**
	 * Returns all media types
	 * 
	 * @return array 
	 */
	public function getAllMediaTypes()
	{
		return $this->media_types->getAll();
	}

	/**
	 * Enables media types
	 * 
	 * @param  array $types List of media type IDs to enable 
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	public function enableMediaTypes(array $types)
	{
		foreach ($types as $type) {

			if ($this->media_types->hasKey($type))
				$this->enabled_media_types->push($type);
		}

		return $this;
	}

	/**
	 * Disables media types
	 * 
	 * @param  array $types List of media type IDs to enable
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery 
	 */
	public function disableMediaTypes(array $types)
	{
		foreach ($types as $type) {
			
			if ($this->media_types->hasKey($type))
				$this->enabled_media_types->pop($type);
		}

		return $this;
	}

	/**
	 * Get the content list object
	 * 
	 * @return object Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList
	 */
	public function getContentList()
	{
		return $this->content_list;
	}

	/**
	 * Returns HTML for the "add" form element
	 * 
	 * @return string
	 */
	public function getFormAddElementTemplate()
	{ 
		ob_start(); 

		if ($this->enabled_media_types->count() == 1) { ?>
		 
			<input bebop-list--formElId="selector" type="hidden" value="<?php echo reset($this->enabled_media_types->getAll()); ?>">

		<?php } else { ?>

			<select bebop-list--formElId="selector" style="max-width:260px">
				
				<option value="">Select a media type</option>
				<?php foreach ($this->media_types as $media_type) { ?>
					
					<?php $type = $media_type->getId();

					if ($this->enabled_media_types->hasValue($type)) { ?>

						<option value="<?php echo $media_type->getId(); ?>">
							<?php echo $media_type->getId(); ?>
						</option>

					<?php }
				} ?>

			</select>

		<?php } ?>

		<button bebop-list--formAction="__addBebopUIGalleryItem" class="button button-primary">
			Add <span class="bebop-ui-icon-add"></span>
		</button>
		
		<?php 

		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	/**
	 * Returns the compiled browse template for all media types
	 * 
	 * @return string HTML for the reorder template
	 */
	public function getBrowseTemplate()
	{
		ob_start();

		foreach ($this->media_types->getAll() as $media_type) {

			$type = $media_type->getId();

			if ($this->enabled_media_types->hasValue($type)) {
				
				$template = $media_type->getTemplate('browse');

				echo "{{#type_is_$type}}";
				include $template;
				echo "{{/type_is_$type}}";
			}
		}

		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	/**
	 * Returns the compiled edit template for all media types
	 * 
	 * @return string HTML for the reorder template
	 */
	public function getEditTemplate()
	{
		ob_start();

		foreach ($this->media_types->getAll() as $media_type) {

			$type = $media_type->getId();

			if ($this->enabled_media_types->hasValue($type)) {

				$template = $media_type->getTemplate('edit');

				echo "{{#type_is_$type}}";
				include $template;
				echo "{{/type_is_$type}}";
			}
		}

		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	/**
	 * Returns the compiled reorder template for all media types
	 * 
	 * @return string HTML for the reorder template
	 */
	public function getReorderTemplate()
	{
		ob_start();

		foreach ($this->media_types->getAll() as $media_type) {

			$type = $media_type->getId();

			if ($this->enabled_media_types->hasValue($type)) {

				$template = $media_type->hasTemplate('reorder') ? $media_type->getTemplate('reorder') : $media_type->getTemplate('browse');

				echo "{{#type_is_$type}}";
				include $template;
				echo "{{/type_is_$type}}";
			}
		}

		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	/**
	 * Renders the Content List configured by the Gallery plugin
	 * 
	 * @return object Ponticlaro\Bebop\UI\Plugins\Gallery
	 */
	public function render()
	{
		$this->content_list
		     ->setFormElement('main', 'add', $this->getFormAddElementTemplate())
		     ->setItemView('browse', $this->getBrowseTemplate())
		     ->setItemView('edit', $this->getEditTemplate())
		     ->setItemView('reorder', $this->getReorderTemplate())
		     ->render();

		return $this;
	}
}