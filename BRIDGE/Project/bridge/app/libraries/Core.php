<?php
# DESCRIPTION
/**
 * URL formatter {Controller}/{Action}/{Params}
 * Finds the right Controller, Action (Method) and params
 */

class Core
{
    # ATTRIBUTES
    private $default = [
        'controller' => 'Pages',
        'action' => 'index',
        'params' => []
    ];

    # CONSTRUCTOR
    public function __construct()
    {
        $url = $this->getURL();

        // Check if the controller is set {Controller}?
        if (isset($url[0]))
            // Check if the controller exists
            if (file_exists(APP_URL . '/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
                // Set it as the default controller
                $this->default['controller'] = ucfirst($url[0]);

                // Unset index 0
                unset($url[0]);
            }

        // Load the controller
        require_once APP_URL . '/controllers/' . $this->default['controller'] . 'Controller.php';

        // Controller instance
        $controller = $this->default['controller'] . 'Controller';
        $this->default['controller'] = new  $controller;

        // The same for the action
        if (isset($url[1])) {
            if ($controller == 'UController' || $controller == 'ActivitiesController') {
                $this->default['params'] = explode('\0', $url[1]);
            } elseif (method_exists($this->default['controller'], $url[1])) {
                // Set it as the default action
                $this->default['action'] = $url[1];

                // Unset index 1
                unset($url[1]);
            }
        }

        // Fill params
        if ($controller != 'UController' && $controller != 'ActivitiesController')
            $this->default['params'] = isset($url[2]) ? array_values($url) : [];

        // Doing the action of the controller with params
        call_user_func_array([$this->default['controller'], $this->default['action']], $this->default['params']);
    }

    # METHODS
    // GET: ?url and return it as an array
    public function getURL()
    {
        return isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url']), FILTER_SANITIZE_URL)) : null;
    }
}
