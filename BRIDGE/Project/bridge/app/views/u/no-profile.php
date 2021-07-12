<?php
# DESCRIPTION
/**
 * This is the profile page contains information of the username passed in the param
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main id="mainUserContainer">
    <!-- Profile aside -->
    <aside class="my-profile">
        <!-- Profile flammes last 7 days -->
        <div class="article alert">
            <img class="image" src="<?php getFileURL('vlt5a3625swwwtkpcad7.svg'); ?>" alt="Image">
            <div class="text">Cette page est indisponible sur <?php echo SITE_NAME; ?>!</div>
        </div>
        <?php
        // Load footer
        require_once APP_URL . '/views/includes/footer.php';
        ?>
    </aside>

    <!-- Connected users aside-->
    <aside class="connected-users-aside article i-pd sticky">
        <h5 class="article-title pd-t-10 pd-x-10"><i class="fas fa-link fa-fw"></i><span>Amis</span></h5>
        <?php
        require_once APP_URL . '/views/includes/data/connected-users.php';
        ?>
    </aside>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>