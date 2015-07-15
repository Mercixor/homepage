<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 14.07.2015
 * Time: 00:16
 */

namespace libs;


class MainpageFactory
{
    protected $content = '';

    protected function buildHead() {
        $html = '<!DOCTYPE html>
                <html>
                    <head>
                        <link type="text/css" rel="stylesheet" href="js/jquery_ui/jquery-ui.css">
                        <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
                        <script type="text/javascript" src="js/jquery_ui/jquery-ui.js"></script>
                        <script type="text/javascript" src="js/jsHandler.js"></script>
                    </head>';
        return $html;
    }

    protected function buildBody() {
        $html = '<body>' .$this->content. '</body>';
        return $html;
    }

    protected function buildFooter() {
        $html = '</html>';
        return $html;
    }

    public function setContent( $content ) {
        $this->content = $content;
        return $this;
    }

    public function getHtml() {
        $html   = $this->buildHead();
        $html  .= $this->buildBody();
        $html  .= $this->buildFooter();
        return $html;
    }
}