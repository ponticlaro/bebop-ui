<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Html\Elements\Select as SelectElement;

class Searchbox extends Select {

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
    		'style' => 'width:100%;display:block'
    	],
      'data_source' => [
      	'url'   => '/_bebop/api/search',
      	'query' => [
      		'max_results' => 25
      	]
      ],
      'templates' => [
      	'result'    => '
          
        ',
      	'selection' => ''
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-select bebop-ui-mod-searchbox">'
    ]);
  }

  /**
   * Ran before rendering module
   * 
   * @return void
   */
  public function preRendering()
  {
    parent::preRendering();

    $config = preg_replace(["/'/", '/"/'], ["&#39;", "&#34;"], json_encode([
    	'placeholder' => $this->getVar('placeholder'),
    	'url'         => $this->getVar('data_source.url'),
    	'query'       => $this->getVar('data_source.query'),
    	'templates'   => $this->getVar('templates')
    ]));

    $this->el->setAttr('bebop-ui-searchbox');
    $this->el->setAttr('data-config', $config);

    $data = $this->getVar('value');

    if (is_array($data)) {
      foreach ($data as $v) {
        $this->el->addOption(get_the_title((int)$v), (string)$v, true);
      }
    }

    elseif ($data) {
      $this->el->addOption(get_the_title((int)$data), (string)$data, true);
    }
  }

  public function renderMainTemplate($variation = 'default')
  {
    if (!$variation || !is_string($variation) || $variation == 'default') {
      $this->el->render();
    }

    elseif ($variation == 'mustache') {
      # code...
    }
  }
}

