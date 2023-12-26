<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Base;

abstract class AbstractPage extends AbstractComponent
{
    /**
     * @var string
     */
    protected $hook_suffix;

    /**
     * @var string
     */
    protected $page_title;

    /**
     * @var string
     */
    protected $menu_title;

    /**
     * @var string
     */
    protected $permission;

    /**
     * @var AbstractField[]
     */
    protected $option_fields;

    /**
     * AbstractPage constructor.
     * @param string $id
     * @param string $page_title
     * @param string $menu_title
     * @param string $permission
     */
    public function __construct($id, $page_title, $menu_title, $permission)
    {
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->permission = $permission;

        $this->option_fields = array();
        parent::__construct($id, null);
    }

    protected function addComponent($section)
    {
        if (! $section instanceof AbstractSection) {
            throw new \Exception(array_pop(explode('\\', get_class($this))) . ' accept only Section.');
        }
        parent::addComponent($section);
    }

    public function render()
    {
        ?>
        <div class="wrap">
            <h2><?php echo $this->page_title ?></h2>

            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                settings_fields($this->id);

                // render all child component
                $this->renderAll();

                submit_button();
                ?>
            </form>


        </div>
        <?php
    }

    public function getTitle()
    {
        return $this->page_title;
    }

    public function init()
    {
        foreach ($this->option_fields as $option_group_name => $fields) {
            register_setting($this->id, $option_group_name);
        }

        // init all child component
        $this->initAll();
    }

    public function getOptionFields()
    {
        return $this->option_fields;
    }

    protected function notifyChildAdd($component)
    {
        if ($component instanceof AbstractField) {
            $option = $component->getOption();
            $option_group = $option->getGroupName();
            $option_id = $option->getID();

            if (empty($option_group)) {
                $this->option_fields[$option_id] = $component;
            } else {
                if (! isset($this->option_fields[$option_group])) {
                    $this->option_fields[$option_group] = array();
                }

                $this->option_fields[$option_group][$component->id] = $component;
            }
        }
    }


    abstract public function initMenu();

    /**
     * @return array
     */
    public function getHookSuffixes()
    {
        return array($this->hook_suffix);
    }
}
