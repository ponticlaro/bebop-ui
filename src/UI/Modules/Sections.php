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
  protected $section_types = [
    'html' => [
      "title"         => "HTML",
      "edit_sections" => [
        [
          "ui"    => "input",
          "label" => "Title"
        ],
        [
          "ui"    => "textarea",
          "label" => "Content"
        ]
      ],
      "browse_sections" => [
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
  public function setType($id, array $config = [])
  {
    $id                         = Utils::slugify($id);
    $this->section_types[$id] = $config;
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
      'before'                => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-pagesections">',
      'set_section_types'     => [],
      'enabled_section_types' => [
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

    // Get user defined section types
    $set_section_types = $this->getVar('set_section_types') ?: [];

    // Add Section types
    if ($set_section_types) {
      foreach ($set_section_types as $section_type_config) {

        $title = isset($section_type_config['title']) && $section_type_config['title'] ? $section_type_config['title'] : null;

        if ($title) {
          $section_type_id = isset($section_type_config['id']) && $section_type_config['id'] ? $section_type_config['id'] : Utils::slugify($title);
          $this->setType($section_type_id, $section_type_config);
        }
      }
    }

    // Handle enabled section types
  	$section_types = $this->getVar('enabled_section_types') ?: [];

  	if ($section_types) {

  		$selector_options = [];
  		$browse_view      = [];
      $reorder_view     = [];
  		$edit_view        = [];

  		foreach ($section_types as $id) {
  			
        $id    = Utils::slugify($id);
        $sconf = isset($this->section_types[$id]) ? $this->section_types[$id] : null;

  			// Get section title
        $title = $sconf['title']; 
  			
  			if ($title) {

  				// Get section type and sections
  				$id               = isset($sconf['id']) && $sconf['id'] ? $sconf['id'] : Utils::slugify($title);
  				$browse_sections  = isset($sconf['browse_sections']) && is_array($sconf['browse_sections']) ? $sconf['browse_sections'] : [];
          $reorder_sections = isset($sconf['reorder_sections']) && is_array($sconf['reorder_sections']) ? $sconf['reorder_sections'] : [];
          $edit_sections    = isset($sconf['edit_sections']) && is_array($sconf['edit_sections']) ? $sconf['edit_sections'] : [];

          if (!$reorder_sections)
            $reorder_sections = $browse_sections;

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

          foreach ($browse_sections as $section) {
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

          foreach ($reorder_sections as $section) {
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

  				foreach ($edit_sections as $section) {
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