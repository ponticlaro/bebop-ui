<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Html\Elements\Select as SelectElement;

class SearchableSelect extends Select {

  /**
   * Sets module main element
   * 
   * @param object $el Module main element
   */
  public function setEl(SelectElement $el)
  {
    $this->el = $el;

    return $this;
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
      'attrs' => [
        'style' => 'min-width:100%;max-width:100%;display:block'
      ],
      'placeholder' => 'Search',
      'url'         => '/_bebop/api/search',
      'query'       => [
        'max_results' => 25
      ],
      'mapping' => [
        'id'   => 'ID',
        'text' => 'post_title'
      ],
      'templates' => [
        'engine'    => 'underscore',
        'result'    => '<strong><%= text %></strong><br><%= post_type %>',
        'selection' => '<%= text %>'
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-select bebop-ui-mod-searchableselect">'
    ]);
  }

  /**
   * Checks if module configuration allows it to be rendered
   * 
   * @return void
   */
  public function configIsValid()
  {
    return $this->getVar('url') && $this->getVar('query') ? true : false;
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    parent::preRendering();

    // Prepare configuration array to be displayed as a DOM element attribute
    $config = preg_replace(["/'/", '/"/'], ["&#39;", "&#34;"], json_encode([
      'placeholder' => $this->getVar('placeholder'),
      'url'         => $this->getVar('url'),
      'query'       => $this->getVar('query'),
      'mapping'     => $this->getVar('mapping'),
      'templates'   => $this->getVar('templates')
    ]));

    $this->el->setAttr('bebop-ui-el--searchableselect', $config);

    $data = $this->getVar('value');

    if (is_array($data)) {
      foreach ($data as $v) {
        $this->el->addOption($v, (string)$v, true);
      }
    }

    elseif ($data) {
      $this->el->addOption($data, (string)$data, true);
    }
  }
}