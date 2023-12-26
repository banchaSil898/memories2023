<?php

namespace UDAdsManager\Core\AdRenderer;

use UDAdsManager\Plugin;

abstract class AbstractAdRenderer
{

    protected $ad_items;

    public function addAdItem($ad_item)
    {
        $ad_id = $ad_item[Plugin::AD_INFO_OPTION_KEY]->name;
        $this->ad_items[$ad_id] = $ad_item;
    }

    abstract public function renderHead();

    abstract public function renderFooter();

    abstract public function getAdItemTag($ad_id);
}
