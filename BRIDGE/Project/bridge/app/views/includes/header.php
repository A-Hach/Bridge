<?php
# DESCRIPTION
/**
 * Loads scripts, CSS files, frameworks and so on...
 * Loads the header
 */
?>

<!DOCTYPE html>
<html lang="fr" data-dark="<?php echo $data['page_info']['dark_mode'] ? 'true' : 'false'; ?>" id="top">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['page_info']['title']; ?></title>
    <link rel="shortcut icon" href="<?php concat(MEDIA_URL, 'favicon.ico'); ?>" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php concat(ROOT_URL, 'content/bootstrap.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?php concat(ROOT_URL, 'scripts/jquery.min.js'); ?>"></script>
    <!-- Bootstrap JS -->
    <script src="<?php concat(ROOT_URL, 'scripts/bootstrap.min.js'); ?>"></script>
    <!-- Fontawsome -->
    <link rel="stylesheet" href="<?php concat(ROOT_URL, 'fonts/css/all.min.css'); ?>">
    <!-- Style -->
    <link rel="stylesheet" href="<?php concat(ROOT_URL, 'css/style.css'); ?>">
    <!-- Dimensions -->
    <link rel="stylesheet" href="<?php concat(ROOT_URL, 'css/dimensions.css'); ?>">
    <!-- Chart JS -->
    <script src="<?php concat(ROOT_URL, 'scripts/chart.min.js'); ?>"></script>
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="<?php concat(ROOT_URL, 'content/plyr.css'); ?>">
    <!-- Plyr JS -->
    <script src="<?php concat(ROOT_URL, 'scripts/plyr.js'); ?>"></script>
    <!-- Data JS -->
    <script src="<?php concat(ROOT_URL, 'js/data.js'); ?>"></script>
    <?php if (isset($data['is_room'])) : ?>
        <!-- Socket.io JS -->
        <script defer src="http://<?php echo HOST; ?>:3000/socket.io/socket.io.js"></script>
        <!-- Script -->
        <script defer src="<?php concat(ROOT_URL, 'js/script.js'); ?>"></script>
    <?php endif; ?>
</head>

<body>
    <!-- Go back to the top -->
    <div id="scrollToBody">
        <a href="#top">
            <svg width="30" height="30" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 12.5C0 19.3927 5.60728 25 12.5 25C19.3927 25 25 19.3927 25 12.5C25 5.60728 19.3927 0 12.5 0C5.60728 0 0 5.60728 0 12.5ZM13.2365 8.63853L18.4448 13.8469C18.6479 14.05 18.75 14.3167 18.75 14.5833C18.75 14.85 18.6479 15.1167 18.4448 15.3198C18.0375 15.7271 17.3792 15.7271 16.9719 15.3198L12.5 10.8479L8.02813 15.3198C7.62085 15.7271 6.9625 15.7271 6.55522 15.3198C6.14795 14.9125 6.14795 14.2542 6.55522 13.8469L11.7636 8.63853C12.1708 8.23125 12.8292 8.23125 13.2365 8.63853Z" fill="#6C63FF" />
            </svg>
        </a>
    </div>

    <!-- Beginning header -->
    <header>
        <?php
        // Load navigation bar
        require_once APP_URL . '/views/includes/navbar/' . $data['page_info']['nav_bar'] . '.php';
        ?>
    </header>
    <!-- Ending header -->

    <!-- Beginning main page content -->
    <main id="main-page-content">
        <!-- Loading -->
        <div id="loading-modal">
            <div class="circle"></div>
        </div>