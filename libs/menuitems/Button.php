<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 15.07.2015
 * Time: 22:38
 */

namespace libs\menuitems;


class Button extends HtmlEntinity {

    public function __construct( $class = '', $id = '', $type = '' ) {
        parent::__construct('<button','>','</button>', $class, $id, $type);
        return $this;
    }
}