<?php
# DESCRIPTION
/**
 * Redirect to a file by name
 * media.bridge/{file_name}
 */

// GET: ?url value [array|null]
$url = isset($_GET['filename']) ? explode('/', filter_var(rtrim($_GET['filename']), FILTER_SANITIZE_URL)) : null;

// Find the file name in the index [0]
$filename = is_null($url) ? 'zuk9jek44ejwovylql3h.jgp' : !file_exists('media/' . $url[0]) ? 'zuk9jek44ejwovylql3h.jpg' : $url[0];

// Redirect to the file
header('location: media/' . $filename);
