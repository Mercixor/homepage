<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 13.07.2015
 * Time: 23:44
 */

namespace libs\menuitems;


class DivContainer extends HtmlEntinity {

    public function __construct( $class = '', $id = '', $type = '' ) {
        parent::__construct('<div','>','</div>', $class, $id, $type);
        $this->canHaveChilds = true;
        return $this;
    }

}