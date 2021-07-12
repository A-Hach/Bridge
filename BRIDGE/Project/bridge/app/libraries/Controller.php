<?php
# DESCRIPTION
/**
 * Base/Parent controller
 * Containt view & model methods
 */

class Controller
{
    # METHODS
    // Load view with some data
    public function view($view, $data = [])
    {
        require_once APP_URL . '/views/' . $view;
    }

    // Returns model instance
    public function model($model)
    {
        require_once APP_URL . '/models/' . $model . '.php';
        return new $model;
    }
}
