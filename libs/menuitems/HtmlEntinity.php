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
    protected $htmlStartTag, $htmlStartEndTag, $htmlEndTag;
    protected $content;
    protected $child, $parent;
    protected $canHaveChilds = false;

    public function __construct($htmlStartTag = '', $htmlStartEndTag = '', $htmlEndTag = '', $class = '', $id = '', $type = '') {
        $this->htmlStartTag     = $htmlStartTag;
        $this->htmlStartEndTag  = $htmlStartEndTag;
        $this->htmlEndTag       = $htmlEndTag;
        $this->class            = $class;
        $this->id               = $id;
        $this->type             = $type;
        return $this;
    }

    public function buildHtml() {
        $html = $this->htmlStartTag . $this->buildProperties() . $this->htmlStartEndTag;
        if ($this->canHaveChilds) {
            $html .= $this->invokeChildHtml();
        }
        $html .= $this->content;
        $html .= $this->htmlEndTag;
        return $html;
    }

    protected function buildProperties() {
        $properties = '';
        if (isset($this->class)) {
            $properties .= ' class="' .$this->class. '"';
        }
        if (isset($this->id)) {
            $properties .= ' id="' .$this->id. '"';
        }
        if (isset($this->type)) {
            $properties .= ' type="' .$this->type. '"';
        }
        return $properties;
    }

    public function setClass( $class ) {
        $this->class = $class;
        return $this;
    }

    public function setContent( $content ) {
        $this->content = $content;
        return $this;
    }

    public function setId( $id ) {
        $this->id = $id;
        return $this;
    }

    public function setType( $type ) {
        $this->type = $type;
        return $this;
    }

    public function addChild( $child ) {
        if ($this->canHaveChilds) {
            $this->child []= $child;
            return $this;
        }
        throw new \Exception();
    }

    public function removeChild( HtmlEntinity $childToLook ) {
        foreach ($this->child AS $key => $child) {
            if ($child === $childToLook) {
                unset($this->child[$key]);
            }
        }
    }

    public function setParent( HtmlEntinity $parent ) {
        $this->parent = $parent;
        return $this;
    }

    protected function invokeChildHtml() {
        if (isset($this->child)) {
            $html = '';
            foreach ($this->child AS $child) {
                if ($child instanceof HtmlEntinity) {
                    $html .= $child->buildHtml();
                }
            }
            return $html;
        }
    }

}