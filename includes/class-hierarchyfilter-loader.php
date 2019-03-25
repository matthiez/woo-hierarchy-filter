<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://github.com/matthiez/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/includes
 * @author     André Matthies <matthiez@gmail.com>
 */
class HierarchyFilter_Loader
{

    protected $actions;

    protected $filters;

    public function __construct() {
        $this->actions = [];
        $this->filters = [];
    }

    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
        return $hooks;
    }

    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }
    }

}