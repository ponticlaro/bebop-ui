<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\UI\Helpers\ModuleFactory;

class PageSections extends ItemList {

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
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-pagesections">'
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

  	$slist = $this->getVar('sections') ?: [];

  	if ($slist) {

  		$selector_options = [
  			[
  				'label' => 'Select a section type...',
  				'value' => '-1'
  			]
  		];
  		$browse_view      = [];
  		$edit_view        = [];

  		foreach ($slist as $sconf) {
  			
  			// Get section title
  			$title = isset($sconf['title']) && $sconf['title'] ? $sconf['title'] : null;

  			if ($title) {

  				// Get section type and sections
  				$id       = isset($sconf['id']) && $sconf['id'] ? $sconf['id'] : Utils::slugify($title);
  				$sections = isset($sconf['sections']) && is_array($sconf['sections']) ? $sconf['sections'] : [];

  				// Add section option to options list
  				$selector_options[] = [
	  				'label' => $title,
	  				'value' => $id
	  			];	

  				// Set openning tag to enclose HTML for this section type
  				$browse_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{#type_is_$id}}"
  				];

  				// Set 'browse' item view to display the title
  				$browse_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{type_title}}: <strong>{{title}}</strong>"
  				];

  				// Set openning tag to enclose HTML for this section type
  				$browse_view[] = [
  					'ui'   => 'rawHtml',
  					'html' => "{{/type_is_$id}}"
  				];

  				// Set openning tag to enclose HTML for this section type
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

  				// Force 'edit' item view to have a title
  				$edit_view[] = [
  					'ui'    => 'input',
  					'label' => 'Title'
  				];

  				foreach ($sections as $section) {
				  	$edit_view[] = $section;
  				}

  				// Set openning tag to enclose HTML for this section type
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
	        'text'  => 'Add<span class="bebop-ui-icon-add"></span>',
	        'class' => 'button-primary',
	        'attrs' => [
	          'bebop-list--formAction' => 'bebop-ui-action--addPageSection'
	        ]
	      ]
  		]);

  		// Set 'browse' view for list items
  		$this->el->addItemViewSections('browse', $browse_view);

  		// Set 'edit' view for list items
  		$this->el->addItemViewSections('edit', $edit_view);
  	}
  }
}