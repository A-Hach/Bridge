<?php
# DESCRIPTION
/**
 * This is activity details page contains comments ...
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main id="mainUserContainer">
    <!-- Profile aside -->
    <aside class="profile-data-aside sticky">
        <!-- Profile data -->
        <?php
        require_once APP_URL . '/views/includes/data/profile-data.php';
        ?>

        <!-- Profile stats -->
        <div class="profile-stats article">
            <h5 class="article-title"><i class="fas fa-id-badge fa-fw"></i><span>Ça fait <?php echo getElapsedTime($data['user'][0]['registration_date'], true); ?></span></h5>
            <?php
            require_once APP_URL . '/views/includes/data/profile-stats.php';
            ?>
        </div>

        <!-- Profile flammes last 7 days -->
        <div class="article">
            <h5 class="article-title"><i class="fas fa-chart-bar fa-fw"></i><span>Flammes ces 7 jours</span></h5>
            <?php
            require_once APP_URL . '/views/includes/data/last-seven-days-flames.php';
            ?>
        </div>

        <!-- Friends around the world -->
        <div class="article">
            <h5 class="article-title"><i class="fas fa-globe-africa fa-fw"></i><span>Vos amis dans le monde</span></h5>
            <?php
            require_once APP_URL . '/views/includes/data/friends-around-world.php';
            ?>
        </div>

        <!-- Most 3 reacted activities -->
        <div class="article most-liked-activities">
            <h5 class="article-title"><i class="fas fa-chart-line fa-fw"></i><span>Activités importantes</span></h5>
            <?php
            // Load most 3 reacted activities
            require_once APP_URL . '/views/includes/data/lovely-activities.php';
            ?>
        </div>

        <?php
        // Load footer
        require_once APP_URL . '/views/includes/footer.php';
        ?>
    </aside>
    <!-- Main aside -->
    <aside class="activity-details-aside">
        <!-- Post details -->
        <div class="article">
            <h5 class="article-title i-mr"><i class="fas fa-copy fa-fw"></i><span>Activité de <?php echo $data['post']['display_name']; ?></span></h5>
        </div>
        <?php
        require_once APP_URL . '/views/includes/data/activity-details.php';
        ?>
    </aside>

    <!-- Connected users aside-->
    <aside class="connected-users-aside article i-pd sticky">
        <h5 class="article-title pd-t-10 pd-x-10"><i class="fas fa-link fa-fw"></i><span>Connectés</span></h5>
        <?php
        // Load connected users
        require_once APP_URL . '/views/includes/data/connected-users.php';
        ?>
    </aside>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>