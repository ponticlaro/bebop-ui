<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\UI\Helpers\MediaSourcesFactory;
use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;
use Ponticlaro\Bebop\Common\Utils;

class MultimediaGallery extends ItemList {

  /**
   * List of media sources allowed for this module
   * 
   * @var string
   */
  protected static $allowed_media_sources = [
    'internal',
    'google_map',
    'soundcloud_track',
    'soundcloud_playlist',
    'types',
    'vimeo',
    'youtube_video',
    'youtube_playlist'
  ];

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    $this->setVars([
      'media_sources' => [
        'internal'            => true,
        'google_map'          => true,
        'soundcloud_track'    => true,
        'soundcloud_playlist' => true,
        'types'               => false,
        'vimeo'               => true,
        'youtube_video'       => true,
        'youtube_playlist'    => true
      ],
      'config' => [
        'labels' => [
          'add_button' => 'Add Media'
        ]
      ],
      'item_views' => [
        'browse' => [
          [
            'ui'   => 'rawHtml',
            'html' => '
              {{#title}}<div class="bebop-ui-mod-list--item-title">{{title}}</div>{{/title}}
              {{#credits}}<div class="bebop-ui-mod-list--item-credits">{{credits}}</div>{{/credits}}
            '
          ]
        ],
        'edit' => [
          [
            'ui'    => 'input',
            'label' => 'Title'
          ],
          [
            'ui'    => 'input',
            'label' => 'Credits'
          ],
          [
            'ui'    => 'textarea',
            'label' => 'Caption'
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-multimediagallery">'
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
    // If there is no name set it as a "slugified" label
    if ($this->getVar('label') && !$this->getVar('name'))
      $this->setVar('name', Utils::slugify($this->getVar('label')));

    $this->el = new ContentList($this->getVar('name'), [], $this->getVar('config'));

    $item_views    = $this->getVar('item_views');
    $media_sources = $this->getVar('media_sources') ?: [];

    // Handle media sources dropdrown
    if ($media_sources) {

      $selector_options = [];

      foreach ($media_sources as $source_id => $source_config) {
        if ($source_config && $this->allowsMediaSource($source_id) && MediaSourcesFactory::canManufacture($source_id)) {

          $media_source = MediaSourcesFactory::create($source_id, is_array($source_config) ? $source_config : []);

          $selector_options[] = [
            'value' => $source_id,
            'label' => $media_source->getName()
          ];
        }
      }

      // Replace 'add' form element
      $total_selector_options = count($selector_options);

      if ($total_selector_options > 0) {
        
        $ui_elements = [];

        if ($total_selector_options == 1) {

          $single_option = reset($selector_options);

          $ui_elements[] = [
            'ui'    => 'input',
            'name'  => true,
            'value' => $single_option['value'],
            'attrs' => [
              'bebop-list--formElId' => 'selector',
              'type'                 => 'hidden'
            ]
          ];
        }

        else {

          array_unshift($selector_options, [
            'value' => '',
            'label' => 'Select Source...'
          ]);

          $ui_elements[] = [
            'ui'    => 'select',
            'name'  => true,
            'attrs' => [
              'bebop-list--formElId' => 'selector'
            ],
            'options' => $selector_options
          ];
        }

        $ui_elements[] = [
          'ui'    => 'button',
          'text'  => '<span class="bebop-ui-icon-add"></span> Add',
          'class' => 'button-primary',
          'attrs' => [
            'bebop-list--formAction' => 'bebop-ui-action--addMediaFromTargetSource'
          ]
        ];

        $this->el->setFormElement('main', 'add', $ui_elements);
      }
    }

    // Handle UI sections
    if ($item_views && is_array($item_views)) {
      foreach ($item_views as $view => $sections) {

        $view_sections = [];
        $sections      = is_array($sections) ? $sections : [];

        if ($media_sources) {
          foreach ($media_sources as $source_id => $source_config) {
            if ($source_config && $this->allowsMediaSource($source_id) && MediaSourcesFactory::canManufacture($source_id)) {

              $media_source = MediaSourcesFactory::create($source_id, is_array($source_config) ? $source_config : []);
              $id_field     = $media_source->getIdentifierField();

              // Set openning tag to enclose HTML for this media source
              $opening_html = '<div bebop-ui-mod-list--item-with-media-source-visual';

              if ($view != 'edit')
                $opening_html .= ' {{^'. $id_field .'}}status-is="warning"{{/'. $id_field .'}}';

              $opening_html .= '>';

              $media_source_sections = [
                [
                  'ui'   => 'rawHtml',
                  'html' => "{{#source_id_is_$source_id}}$opening_html"
                ]
              ];

              // - Add hidden input with media source
              if ($view == 'edit') {
                $media_source_sections[] = [
                  'ui'    => 'hidden',
                  'name'  => 'source_id',
                  'value' => '{{source_id}}'
                ];

                $media_source_sections[] = [
                  'ui'    => 'hidden',
                  'name'  => 'source_name',
                  'value' => '{{source_name}}'
                ];
              }

              // Add media source sections
              $source_config         = is_array($source_config) ? $source_config : [];
              $media_source_sections = array_merge($media_source_sections, $media_source->getContentListUISections($view, $source_config));

              // Add user defined sections
              $media_source_sections = array_merge($media_source_sections, $sections);

              // Set closing tag to enclose HTML for this media source
              $media_source_sections[] = [
                'ui'   => 'rawHtml',
                'html' => "</div>{{/source_id_is_$source_id}}"
              ];

              // Add media source sections to current view list of sections
              if ($media_source_sections)
                $view_sections = array_merge($view_sections, $media_source_sections);
            }
          }
        }

        if ($view_sections && is_array($view_sections))
          $this->el->addItemViewSections($view, $view_sections);
      }
    }
  }

  /**
   * Checks if media source is allowed
   * 
   * @param  string  $id Media source ID
   * @return boolean     True if media source is available, false otherwise
   */
  protected function allowsMediaSource($id)
  {
    return is_string($id) && in_array($id, static::$allowed_media_sources) ? true : false;
  }
}