<?php 

namespace Ponticlaro\Bebop\Html;

interface ElementInterface
{ 
  public function setTag($tag);
  public function getTag();
  public function setId($id);
  public function getId();
  public function setName($name);
  public function getName();
  public function setValue($value);
  public function getValue();
  public function setClasses(array $classes);
  public function addClass($class);
  public function getClass();
  public function setAttrs(array $attrs);
  public function setAttr($name, $value = null);
  public function removeAttrs(array $attrs);
  public function removeAttr($name);
  public function getAttrs();
  public function getAttr($name);
  public function setParent(ElementAbstract $el);
  public function hasParent();
  public function getParent();
  public function append($el);
  public function prepend($el);
  public function getChildren();
  public function getOpeningTag();
  public function getClosingTag();
  public function isSelfClosing();
  public function getHtml();
  public function render();
  public function __toString();
}