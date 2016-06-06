<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\UI\Helpers\ModuleFactory;

class Sections extends ItemList {

  /**
   * List of available section types
   * 
   * @var array
   */
  protected static $section_types = [
    'html' => [
      "title"        => "HTML",
      "edit_modules" => [
        [
          "ui"    => "input",
          "label" => "Title"
        ],
        [
          "ui"    => "textarea",
          "label" => "Content"
        ]
      ],
      "browse_modules" => [
        [
          "ui"   => "rawHtml",
          "html" => "{{type_title}}: <strong>{{title}}</strong>"
        ]
      ]
    ]
  ];

  /**
   * Sets a single section type
   * 
   * @param string $id     Section type ID
   * @param array  $config Section type Configuration array
   */
  public static function setType($id, array $config = [])
  {
    $id                         = Utils::slugify($id);
    static::$section_types[$id] = $config;
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
      'before'            => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-pagesections">',
      'set_section_types' => [],
      'section_types'     => [
        'html'
      ]
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
  	parent::__afterSetVars();

    $set_section_types = $this->getVar('set_section_types') ?: [];

    // Add Section types
    if ($set_section_types) {
      foreach ($set_section_types as $section_type_config) {

        $title = isset($section_type_config['title']) && $section_type_config['title'] ? $section_type_config['title'] : null;

        if ($title) {
          $section_type_id = Utils::slugify($title);
          $this->setType($section_type_id, $section_type_config);
        }
      }
    }

    // Handle enabled section types
  	$section_types = $this->getVar('section_types') ?: [];

  	if ($section_types) {

  		$selector_options = [];
  		$browse_view      = [];
      $reorder_view     = [];
  		$edit_view        = [];

  		foreach ($section_types as $id) {
  			
        $id    = Utils::slugify($id);
        $sconf = isset(static::$section_types[$id]) ? static::$section_types[$id] : null;

  			// Get section title
        $title = $sconf['title']; 
  			
  			if ($title) {

  				// Get section type and sections
  				$id           = isset($sconf['id']) && $sconf['id'] ? $sconf['id'] : Utils::slugify($title);
  				$browse_modules  = isset($sconf['browse_modules']) && is_array($sconf['browse_modules']) ? $sconf['browse_modules'] : [];
          $reorder_modules = isset($sconf['reorder_modules']) && is_array($sconf['reorder_modules']) ? $sconf['reorder_modules'] : [];
          $edit_modules = isset($sconf['edit_modules']) && is_array($sconf['edit_modules']) ? $sconf['edit_modules'] : [];

          if (!$reorder_modules)
            $reorder_modules = $browse_modules;

  				// Add section option to options list
  				$selector_options[] = [
	  				'label' => $title,
	  				'value' => $id
	  			];	

          /////////////////
          // Browse View //
          /////////////////
          
  				// Set opening tag to enclose HTML for this section type
  				$browse_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{#type_is_$id}}"
  				];

          foreach ($browse_modules as $section) {
            $browse_view[] = $section;
          }

  				// Set closing tag to enclose HTML for this section type
  				$browse_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{/type_is_$id}}"
  				];

          //////////////////
          // Reorder View //
          //////////////////

          // Set opening tag to enclose HTML for this section type
          $reorder_view[] = [
            'ui'   => 'rawHtml',
            'html' => "{{#type_is_$id}}"
          ];

          foreach ($reorder_modules as $section) {
            $reorder_view[] = $section;
          }

          // Set closing tag to enclose HTML for this section type
          $reorder_view[] = [
            'ui'   => 'rawHtml',
            'html' => "{{/type_is_$id}}"
          ];

          ///////////////
          // Edit View //
          ///////////////

  				// Set opening tag to enclose HTML for this section type
  				$edit_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{#type_is_$id}}"
  				];

  				// Add hidden input with section type id'
  				$edit_view[] = [
  					'ui'    => 'hidden',
  					'name'  => 'type',
  					'value' => $id
  				];

  				// Add hidden input with section type 'title'
  				$edit_view[] = [
  					'ui'    => 'hidden',
  					'name'  => 'type_title',
  					'value' => $title
  				];

  				foreach ($edit_modules as $section) {
				  	$edit_view[] = $section;
  				}

  				// Set closing tag to enclose HTML for this section type
  				$edit_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{/type_is_$id}}"
  				];
  			}
  		}

  		// Replace 'add' for element
  		$this->el->setFormElement('main', 'add', [
  			[
  				'ui'    => 'select',
  				'name'  => true,
  				'attrs' => [
		        'bebop-list--formElId' => 'selector'
		      ],
  				'options' => $selector_options
  			],
  			[
	        'ui'    => 'button',
	        'text'  => '<span class="bebop-ui-icon-add"></span> Add',
	        'class' => 'button-primary',
	        'attrs' => [
	          'bebop-list--formAction' => 'bebop-ui-action--addSection'
	        ]
	      ]
  		]);

  		// Set 'browse' view for list items
  		$this->el->addItemViewSections('browse', $browse_view);

      // Set 'reorder' view for list items
      $this->el->addItemViewSections('reorder', $reorder_view);

  		// Set 'edit' view for list items
  		$this->el->addItemViewSections('edit', $edit_view);
  	}
  }
}