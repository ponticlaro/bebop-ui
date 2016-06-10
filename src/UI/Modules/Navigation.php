<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Common\Utils;

class Navigation extends Sections {

  /**
   * List of available section types
   * 
   * @var array
   */
  protected static $section_types = [
    'internal_page' => [
      "title"         => "Internal Page",
      "edit_sections" => [
        [
          "ui"    => "postsearch",
          "label" => "Internal Page",
          "name"  => "id",
          "attrs" => [
            "style" => "width:100%"
          ]
        ],
        [
          "ui"          => "input",
          "label"       => "Title",
          "description" => "Used this field to override the entry title"
        ]
      ],
      "browse_sections" => [
        [
          "ui"   => "postsearch",
          "name" => "id"
        ]
      ]
    ],
    'link' => [
      "title"         => "Link",
      "edit_sections" => [
        [
          "ui"    => "input",
          "label" => "Title"
        ],
        [
          "ui"    => "input",
          "label" => "Link"
        ],
        [
          "ui"      => "checkbox",
          "name"    => "open_link_on_new_tab",
          "options" => [
            [
              "value" => "1",
              "label" => "Open link on new tab"
            ]
          ]
        ]
      ],
      "browse_sections" => [
        [
          "ui"   => "rawHtml",
          "html" => "<a target=\"_blank\" href=\"{{link}}\">{{title}}</a>"
        ]
      ]
    ]
  ];

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
      'before'        => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-pagesections bebop-ui-mod-navigation">',
      'section_types' => [
        'Internal Page',
        'Link'
      ],
      'depth'              => 0,
      'browse_sections'    => [],
      'reorder_sections'   => [],
      'edit_sections'      => []
    ]);
  }

  /**
   * Modify module after setting initial user vars
   * 
   * @return void
   */
  protected function __afterSetVars()
  {
    // Collect default section types
    $default_section_types = static::$section_types;

    // Add ui sections to the browse view of all section types
    if ($browse_sections = $this->getVar('browse_sections')) {

      foreach (static::$section_types as $id => $config) {

        foreach ($browse_sections as $section) {
          $config['browse_sections'][] = $section;
        }

        static::$section_types[$id] = $config;
      }
    }

    // Add ui sections to the reorder view of all section types
    if ($reorder_sections = $this->getVar('reorder_sections')) {

      foreach (static::$section_types as $id => $config) {

        foreach ($reorder_sections as $section) {
          $config['reorder_sections'][] = $section;
        }

        static::$section_types[$id] = $config;
      }
    }

    // Add ui sections to the edit view of all section types
    if ($edit_sections = $this->getVar('edit_sections')) {

      foreach (static::$section_types as $id => $config) {

        foreach ($edit_sections as $section) {
          $config['edit_sections'][] = $section;
        }

        static::$section_types[$id] = $config;
      }
    }

    // $depth = $this->getVar('depth');

    // if (is_numeric($depth) && $depth > 0) {

    //   // Loop through each existing section type
    //   foreach (static::$section_types as $id => $config) {

    //     $sections_copy = $config['edit_sections'];
    //     $temp_depth    = $depth;

    //     // For each level add a copy of the parent nav
    //     while ($temp_depth > 0) {

    //       $current_sections                              = static::$section_types[$id]['edit_sections'];
    //       static::$section_types[$id]['edit_sections']   = $sections_copy;
    //       static::$section_types[$id]['edit_sections'][] = [
    //         'ui'                => 'navigation',
    //         'label'             => 'Links',
    //         'section_types'     => $this->getVar('section_types'),
    //         'set_section_types' => static::$section_types[$id]['section_types'],
    //         'edit_sections'     => $current_sections
    //       ];

    //       $temp_depth--;
    //     }
    //   }
    // }

    parent::__afterSetVars();

    // Restore default section types
    static::$section_types = $default_section_types;
  }
}