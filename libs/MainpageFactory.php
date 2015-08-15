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
    protected $menuPoints = array(
        'News' => 'news',
        'Testing' => 'testing'
    );

    protected function buildHead() {
        $html = '<!DOCTYPE html>
                <html>
                    <head>
                        <link type="text/css" rel="stylesheet" href="'. CSS_PATH .'/jquery_ui/jquery-ui.css">
                        <script type="text/javascript" src="'. JS_PATH .'/jquery-2.1.4.js"></script>
                        <script type="text/javascript" src="'. JS_PATH .'/jquery_ui/jquery-ui.js"></script>
                        <script type="text/javascript" src="'. JS_PATH .'/jsHandler.js"></script>
                    </head>';
        return $html;
    }

    protected function buildMenu() {
        $html = '<div id="menu_box">';
        foreach($this->menuPoints AS $menu => $link){
            $html .= '<a href="' . $_SERVER['HTTP_HOST'] . '/libs/module.php?target=' . $link . '">' . $menu .'</a><br />';
        }
        $html .= '</div>';
        return $html;
    }

    protected function buildBody() {
        $html = '<body>
                    <div id="content_container">' .
                        $this->buildMenu(). '
                        <div id="content">' . $this->content. '</div>
                    </div>
                 </body>';
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