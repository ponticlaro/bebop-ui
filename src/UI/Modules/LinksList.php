<?php

namespace Ponticlaro\Bebop\UI\Modules;

class LinksList extends ItemList {

  /**
   * Applies module defaults
   * 
   * @return void
   */
  protected function __init()
  {
    parent::__init();

    $this->setVars([
      'config.labels.add_button' => 'Add Link',
      'before'                   => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-list-linkslist">',
      'item_views'               => [
        'browse' => [
          [
            'ui'   => 'rawHtml',
            'html' => '
              {{#link}}
                <a href="{{link}}" target="_blank">
                  {{#title}}{{title}}{{/title}}
                  {{^title}}
                    {{#link}}{{link}}{{/link}}
                    {{^link}}<em>No link or title to display</em>{{/link}}
                  {{/title}}
                </a>
              {{/link}}
              {{^link}}
                <em>No link to display</em>
              {{/link}}'
          ]
        ],
        'edit' => [
          [
            'ui'    => 'input',
            'label' => 'Title'
          ],
          [
            'ui'    => 'input',
            'label' => 'Link'
          ],
          [
            'ui'      => 'checkbox',
            'name'    => 'open_in_new_window',
            'options' => [
              [
                'label' => 'Open in new window',
                'value' => '1'
              ]
            ]
          ]
        ]
      ]
    ]);
  }
}