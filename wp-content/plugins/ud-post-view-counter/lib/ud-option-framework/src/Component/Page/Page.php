<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Page;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractPage;

if (! defined('ABSPATH')) {
    exit;
}

class Page extends AbstractPage
{

    private $icon;
    private $position;
    private $has_share_slug;
    private $sub_pages;


    /**
     * Page constructor.
     * @param string $id
     * @param string $page_title
     * @param string $menu_title
     * @param string $permission
     * @param string $icon
     * @param string $position
     */
    public function __construct($id, $page_title, $menu_title, $permission, $icon, $position)
    {
        parent::__construct($id, $page_title, $menu_title, $permission);

        $this->icon = $icon;
        $this->position = $position;
        $this->has_share_slug = false;
        $this->sub_pages = array();
    }

    public function initMenu()
    {
        $callback = array($this, 'render');
        if (true === $this->has_share_slug) {
            $callback = null;
        }

        $this->hook_suffix = add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->permission,
            $this->id,
            $callback,
            $this->icon,
            $this->position
        );
    }


    public function addComponent($component)
    {
        if (true === $this->has_share_slug) {
            throw new \Exception('Top Level Page that share slug with it\'s SubPage can\'t have Section');
        }

        parent::addComponent($component);
    }

    public function getAllOptionFields()
    {
        if (true === $this->has_share_slug) {
            return array();
        }

        return parent::getAllOptionFields();
    }

    /**
     * @return SubPage[]
     */
    public function getSubPages()
    {
        return $this->sub_pages;
    }

    public function hasSharedSlugSubPage()
    {
        return $this->has_share_slug;
    }

    /**
     * @param SubPage $sub_page
     */
    public function addSubPage($sub_page)
    {
        if ($this->getID() === $sub_page->getID()) {
            $this->has_share_slug = true;
        }
        array_push($this->sub_pages, $sub_page);
    }
}
