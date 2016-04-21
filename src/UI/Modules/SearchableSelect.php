<?php

namespace Ponticlaro\Bebop\UI\Modules;

use Ponticlaro\Bebop\Db\WpQueryEnhanced;
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
    		'style' => 'width:100%;display:block'
    	],
      'data_source' => [
        'query_type' => 'posts',
        'query'      => [
          'type' => [
            'post',
            'page'
          ],
          'max_results' => -1
        ],
        'mapping' => [
          'value' => 'ID',
          'label' => 'post_title'
        ],
        'grouping' => [
          'by'     => 'post_type',
          'groups' => [
            'post' => 'Posts',
            'page' => 'Pages'
          ]
        ]
      ],
      'before' => '<div class="bebop-ui-mod bebop-ui-mod-select bebop-ui-mod-searchableselect">'
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

    $this->el->setAttr('bebop-ui-searchable-select');
    $this->el->setValue($this->getVar('value'));

    $query = new WpQueryEnhanced($this->getVar('data_source.query'));
    $data  = $query->execute();

    if ($data) {

      $value_property = $this->getVar('data_source.mapping.value');
      $label_property = $this->getVar('data_source.mapping.label');

      foreach ($data as $item) {

        $label = $item->$label_property;
        $value = $item->$value_property;

        if ($label)
           $this->el->addOption($label, (string)$value);
      }
    }

    else {

      $this->el->setAttr('disabled', true);
      $this->el->setAttr('placeholder', 'No items to be displayed');
    }
  }
}