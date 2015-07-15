<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 13.07.2015
 * Time: 23:44
 */

namespace libs\menuitems;


class MenuPoint extends HtmlEntinity
{
    protected $class = 'menupoint';

    public function buildHtml()
    {
        $html = '<div' .$this->buildProperties(). '>';
        $this->invokeChildHtml();
        $html .= '</div>';
        return $html;
    }

}