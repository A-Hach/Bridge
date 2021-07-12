<?php
# DESCRIPTION
/**
 * Defines all application constants
 * Database configurations
 * Wesite configurations
 */

# DATABASE CONFIGURATIONS
if (!defined('DBMS')) define('DBMS', 'mysql');
if (!defined('HOST')) define('HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'bridge');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '$php$my$admin$');

# WEBSITE CONFIGURATIONS
if (!defined('ROOT_URL')) define('ROOT_URL', 'http://' . HOST . '/bridge');
if (!defined('MEDIA_URL')) define('MEDIA_URL', 'http://' . HOST . '/files.bridge');
if (!defined('APP_URL')) define('APP_URL', dirname(dirname(__FILE__)));
if (!defined('SITE_NAME')) define('SITE_NAME', 'Bridge');
if (!defined('DESCRIPTION')) define('DESCRIPTION', 'Restez en contact avec vos amis et vos proches en temps réel et partagez des activités entre vous!');
if (!defined('VERSION')) define('VERSION', '1.0.0');
if (!defined('FILES_FOLDER')) define('FILES_FOLDER', dirname(dirname(APP_URL)) . '/files.bridge/media');

# LOGOS
if (!defined('BLACK_LOGO')) define('BLACK_LOGO', 'ntvb6BYFPeNaXYuzqWD5.svg');
if (!defined('BLACK_ICON')) define('BLACK_ICON', 'Kz3gQo4YX12zx7UlENTB.svg');

if (!defined('WHITE_LOGO')) define('WHITE_LOGO', 'gbCXkjt80myHSXJGbfwG.svg');
if (!defined('WHITE_ICON')) define('WHITE_ICON', '5K0tX1hKbVF4tPZMdtkX.svg');

if (!defined('DARK_LOGO')) define('DARK_LOGO', 'UY9Vwynyb5868i99yTEx.svg');
if (!defined('DARK_ICON')) define('DARK_ICON', 'gbCXkjt80myHSXJGbfwG.svg.svg');

if (!defined('LIGHT_LOGO')) define('LIGHT_LOGO', '7B0wKYcgxSBW3Klcel3S.svg');
if (!defined('LIGHT_ICON')) define('LIGHT_ICON', 'ocrLGnOs6wo8tOHEUB5b.svg');

if (!defined('PURPLE_LOGO')) define('PURPLE_LOGO', 'LchVrUXVIZ9rZr8zor2p.svg');
if (!defined('PURPLE_ICON')) define('PURPLE_ICON', 'RFOAvLtykKhHobBCusnW.svg');

/**
 * @param DBMS Database Management System for $dsn
 * @param HOST Host
 * @param DB_NAME Database Name for $dsn
 * Example
 * $dsn = 'DBMS:host=DB_HOST;dbname=DB_NAME'; ($dsn Value => mysql:host=localhost;dbname=bridge)
 * 
 * @param DB_USER Database User for PDO instance
 * @param DB_PASS Database Password for PDO instance
 * Example
 * $db_handler = new PDO($dsn, DB_USER, DB_PASS);
 * 
 * @param APP_URL Application URL gives us the path to app folder (APP_URL value => C:\xampp\htdocs\bridge\app)
 */
