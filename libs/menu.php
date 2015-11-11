<?php
namespace libs;


use libs\menuitems\HtmlEntinity;

class Menu
{
    static $instance;

    protected $menuitems;

    public static function getInstance() {
        if (isset(self::$instance)) {
            return self;
        } else {
            return new self;
        }
    }

    public function addMenuItem($item) {
        $this->menuitems[] = $item;
        return $this;
    }

    public function buildView() {
        foreach ($this->menuitems AS $item) {
            if ($item instanceof HtmlEntinity) {
                $item->buildHtml();
            }
        }
    }

}