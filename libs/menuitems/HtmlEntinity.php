<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 13.07.2015
 * Time: 23:45
 */

namespace libs\menuitems;


abstract class HtmlEntinity
{
    protected $class, $id, $type;
    protected $child, $parent;

    public abstract function buildHtml();

    public function buildProperties() {
        $properties = '';
        if (isset($this->class)) {
            $properties .= ' class="' .$this->class. '"';
        }
        if (isset($this->id)) {
            $properties .= ' id"' .$this->id. '"';
        }
        if (isset($this->type)) {
            $properties .= ' type="' .$this->type. '"';
        }
        return $properties;
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function addChild($child) {
        $this->child []= $child;
        return $this;
    }

    public function removeChild(HtmlEntinity $childToLook) {
        foreach ($this->child AS $key => $child) {
            if ($child === $childToLook) {
                unset($this->child[$key]);
            }
        }
    }

    public function setParent(HtmlEntinity $parent) {
        $this->parent = $parent;
        return $this;
    }

    public function invokeChildHtml() {
        if (isset($this->child)) {
            foreach ($this->child AS $child) {
                if ($child instanceof HtmlEntinity) {
                    $child->buildHtml();
                }
            }
        }
    }

}