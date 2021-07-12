<?php

# FUNCTIONS TO MAKE THE WAY MORE EASY

// Get file URL to by name redirect to media.bridge
function getFileURL($file_name)
{
    echo MEDIA_URL . '/' . $file_name;
}

// Get number abbreviation
/**
 * 0 à 999
 * 1k à 999k
 * 1m à 999m
 * 1b à 1b
 */
function getNumberAbbr($number)
{
    switch (true) {
        case $number < 1000:
            return $number;

        case $number < 1000000:
            return round(($number / 1000), 1) . 'k';

        case $number < 1000000000:
            return round(($number / 1000000), 1) . 'm';

        case $number < 1000000000000:
            return round(($number / 1000000000), 1) . 'b';

        default:
            return round(($number / 1000000000000), 1) . 't';
    }
}

// Get elapsed time 
/**
 * Il y a quelques secondes
 * Il y a une minute
 * Il y a 2 à 59 minutes
 * Il y a une heure
 * Il y a 2 à 23 heures
 * Il y a un jour
 * Il y a 2 à 6 jours
 * Il y a une semaine
 * Il y a 2 à 4 semaines (4.4)
 * Il y a un mois
 * Il y a 2 à 11 mois
 * Il y a 1 an
 * Il y a 2 à +oo ans
 */
function getElapsedTime($start_date, $has_been = false)
{
    //echo (time() - strtotime('1999/09/20')) / (60 * 60 * 24 * (isLeapYear(date('Y')) ? 366 : 365));
    $time = time() - strtotime($start_date);
    $time -= (60 * 60 * 2);

    switch (true) {
        case $time < 60:
            return !$has_been ? 'Il y a quelques secondes' : $time . ' secondes';
        case floor($time / 60) == 1:
            return !$has_been ? 'Il y a une minute' : 'une minute';
        case floor($time / 60) < 60:
            return  !$has_been ? 'Il y a ' . floor($time / 60) . ' minutes' : floor($time / 60) . ' minutes';
        case floor($time / 3600) == 1:
            return !$has_been ? 'Il y a une heure' : 'une heure';
        case floor($time / 3600) < 24:
            return !$has_been ? 'Il y a ' . floor($time / 3600) . ' heures' : floor($time / 3600) . ' heures';
        case floor($time / 86400) == 1:
            return !$has_been ? 'Hier' : 'un jour';
        case floor($time / 86400) < 7:
            return !$has_been ? 'Il y a ' . floor($time / 86400) . ' jours' : floor($time / 86400) . ' jours';
        case floor($time / 604800) == 1:
            return !$has_been ? 'Il y a une semaine' : 'une semaine';
        case $time / 604800 < 4.4:
            return !$has_been ? 'Il y a ' . floor($time / 604800) . ' semaines' : floor($time / 604800) . ' semaines';
        case floor($time / 2661120) == 1:
            return !$has_been ? 'Il y a un mois' : 'un mois';
        case floor($time / 2661120) < 12:
            return !$has_been ? 'Il y a ' . floor($time / 2661120) . ' mois' : floor($time / 2661120) . ' mois';
        case floor($time / (86400 * (isLeapYear(date('Y')) ? 366 : 365))) == 1:
            return !$has_been ? 'Il y a un an' : 'un an';
        default:
            return !$has_been ? 'Il y a ' . floor($time / 31933440) . ' ans' : floor($time / 31933440) . ' ans';
    }
}

// Check if a year is bisect (Leap)
function isLeapYear($year)
{
    return date('L', strtotime($year . '/01/01')) == 1 ? true : false;
}

// Write the URL instead of <?php echo CONST . '/something'; we will use concat(CONST, sub-path)
function concat($constant, $sub_path)
{
    echo $constant . '/' . $sub_path;
}

// Get date part
function getFromDate($date, $part = 'd')
{
    $time = strtotime($date);
    $date = date($part, $time);

    return $date;
}

// To redirect
function redirect($url)
{
    header('location: ' . ROOT_URL . '/' . $url);
}

// To get a random string
function getRandomString($number)
{
    $characters = 'n7bv5c6x2wml8azke9jhgrtyuf4d3s1i_q0po';
    $random = '';

    for ($i = 0; $i < $number; $i++)
        $random .= $characters[rand(0, strlen($characters) - 1)];

    return $random . '_' . time();
}

// Check if all array element are empty or null
function isEmptyOrNull($elements)
{
    foreach ($elements as $element)
        if (!is_null($element) && !empty($element))
            return false;
    return true;
}

// Get month number by name
function getMonthNumber($month)
{
    $months = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Aout',
        9 => 'Semptembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];

    return array_search($month, $months);
}

// To get social media by name
function getSocialMedia($links, $name, $field)
{
    for ($i = 0; $i < count($links); $i++)
        if (strtolower($links[$i]['name']) == $name)
            return $links[$i][$field];
    return '';
}

function getDateString($year, $month, $day, $lang = 'en')
{
    if ($lang == 'fr')
        return $day . '/' . $month . '/' . $year;
    else
        return $year . '-' . $month . '-' . $day;
}

// Flash message
function flash($name = '', $message = '', $class = 'flash flash-success')
{
    if (!empty($message) && !empty($name)) {
        $_SESSION[$name] = $message;
        $_SESSION[$name . '_class'] = $class;
    } else if (!empty($name)) {
        $div = '<div class="' . $_SESSION[$name . '_class'] . '">';
        $div .= '<div class="flash-msg">' . $_SESSION[$name] . '</div>';
        $div .= '<svg class="close-flash" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="26" height="26" viewBox="0 0 24 24">';
        $div .= '<path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg></div>';
        echo $div;
        unset($_SESSION[$name]);
        unset($_SESSION[$name . '_class']);
    }
}

// To check if a file is Image or Video or Audio
function isValidMedia($filename)
{
    $allowed = [
        'png',
        'jpg',
        'jpeg',
        'mp4',
        'mp3',
        'wav'
    ];

    $extension = pathinfo($filename)['extension'];

    // True if exist, false else
    return in_array($extension, $allowed);
}

// To get file type from extension
function getFileType($filename)
{
    // Get the extension
    $extension = pathinfo($filename)['extension'];

    if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg')
        return 'image';

    if ($extension == 'mp4')
        return 'video';

    if ($extension == 'mp3' || $extension == 'wav')
        return 'audio';

    return false;
}
