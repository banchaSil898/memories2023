<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Page;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractPage;

if (! defined('ABSPATH')) {
    exit;
}

class SubPage extends AbstractPage
{
    protected $parent_page;

    private $parent_page_slug;

    /**
     * OptionPage constructor.
     * @param string      $id
     * @param Page|string $parent_page
     * @param string      $page_title
     * @param string      $menu_title
     * @param string      $permission
     */
    public function __construct($id, $parent_page, $page_title, $menu_title, $permission)
    {
        $this->parent_page = $parent_page;
        parent::__construct($id, $page_title, $menu_title, $permission);

        if ($this->parent_page instanceof Page) {
            $this->parent_page->addSubPage($this);
            $this->parent_page_slug = $this->parent_page->getID();
        } elseif (is_string($parent_page)) {
            $this->parent_page_slug = $parent_page;
        }
    }

    public function initMenu()
    {
        $callback = array($this, 'render');
        $this->hook_suffix = add_submenu_page(
            $this->parent_page_slug,
            $this->page_title,
            $this->menu_title,
            $this->permission,
            $this->getID(),
            $callback
        );
    }
}
