<?php

namespace Ponticlaro\Bebop\UI\Patterns;

use Ponticlaro\Bebop\Common\Collection;

abstract class MediaSourceAbstract {

  /**
   * Media type configuration
   * 
   * @var object Ponticlaro\Bebop\Common\Collection
   */
  protected $config;

  /**
   * Instantiates this class
   * 
   */
  public function __construct()
  {
    $this->config = new Collection();
  }

  /**
   * Returns media source type
   * 
   * @return string Media source id
   */
  public function getID()
  {
    return $this->config->get('id');
  }

  /**
   * Returns media source name
   * 
   * @return string Media source name
   */
  public function getName()
  {
    return $this->config->get('name');
  }

  /**
   * Returns media source identifier field
   * 
   * @return string Media source identifier field
   */
  public function getIdentifierField()
  {
    return $this->config->get('identifier_field');
  }

  /**
   * Renders media based on source
   * 
   * @return void
   */
  abstract public function render();

  /**
   * Returns UI sections to be used with UI Lists
   * 
   * @param  string $view   UI List item view
   * @param  array  $config User configuration
   * @return array          List of UI sections to be rendered
   */
  abstract public static function getContentListUISections($view, array $config);
}