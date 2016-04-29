<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Common\Utils;
use Ponticlaro\Bebop\UI\Plugins\ContentList\ContentList;

class AssociatedTypes extends ItemList {

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
      'placeholder' => 'Search',
      'url'         => null,
      'query'       => [],
      'mapping'     => [],
      'templates'   => [
        'result' => '<div class="bebop-ui-mod-associatedtypes-searchresult"><%= text %><span><%= post_type %></span></div>',
      ],
      'before'      => '<div class="bebop-ui-mod bebop-ui-mod-list bebop-ui-mod-associatedtypes">'
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

    $search_config = [];

    if ($this->getVar('placeholder'))
      $search_config['placeholder'] = $this->getVar('placeholder');

    if ($this->getVar('url'))
      $search_config['url'] = $this->getVar('url');

    if ($this->getVar('query'))
      $search_config['query'] = $this->getVar('query');

    if ($this->getVar('mapping'))
      $search_config['mapping'] = $this->getVar('mapping');

    if ($this->getVar('placeholder'))
      $search_config['templates'] = $this->getVar('templates');

    $config = array_replace_recursive([
      'ui'    => 'postsearch',
      'attrs' => [
        'bebop-list--formElId' => 'selector'
      ]
    ], 
    $search_config, 
    [
      'attrs' => [
        'style' => 'min-width:220px;max-width:100%;display:block'
      ]
    ]);

    $this->el->setFormElement('main', 'add', [
      $config,
      [
        'ui'    => 'button',
        'text'  => '<span class="bebop-ui-icon-add"></span> Add',
        'class' => 'button-primary',
        'attrs' => [
          'bebop-list--formAction' => 'bebop-ui-action--addAssociatedType'
        ]
      ]
    ]);

    $config = array_replace_recursive([
      'ui'   => 'postsearch',
      'name' => 'id',
      'attrs' => [
        'disabled' => true,
        'bebop-ui-mod-associatedtypes-selector' => true
      ]
    ], $search_config);

    $this->el->addItemViewSection('browse', $config);

    $config = array_replace_recursive([
      'ui'   => 'postsearch',
      'name' => 'id',
      'attrs' => [
        'bebop-ui-mod-associatedtypes-selector' => true
      ]
    ], $search_config);

    $this->el->addItemViewSection('edit', $config);

    $item_views = $this->getVar('item_views');

    if ($item_views && is_array($item_views)) {
      foreach ($item_views as $view => $sections) {

        if ($sections && is_array($sections))
          $this->el->addItemViewSections($view, $sections);
      }
    }
  }
}