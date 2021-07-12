<?php
# DESCRIPTION
/**
 * Loads libraries
 */

// Load libraries
spl_autoload_register(function ($library) {
    require_once 'libraries/' . $library . '.php';
});
