<?php
# DESCRIPTION
/**
 * Loads config file (contains all constantes)
 * Loads helpers (contains some helpful functions)
 * Loads bootstrap file
 * Create a Core instance (loaded from bootstrap file)
 */

// Load config file
require_once '../app/config/config.php';

// Set Access-Control-Allow-Origin
header('Access-Control-Allow-Origin: *');

// Start session
session_start();

// Load helpers
require_once APP_URL . '/helpers/functions.php';

// Load bootstrap file
require_once APP_URL . '/bootstrap.php';

// Core instance
$init = new Core;
