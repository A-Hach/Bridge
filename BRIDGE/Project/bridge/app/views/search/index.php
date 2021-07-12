<?php
# DESCRIPTION
/**
 * This is search page contains searching results
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
            // Load friends around the world
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
    <aside class="search-results-aside sticky">
        <div class="c-tab-menu search-tab-menu">
            <div class="c-tab-items noselect">
                <div class="c-tab-item<?php if ($data['page_info']['tab'] == 'activities') echo ' active'; ?>" data-index="0"><i class="fas fa-clone fa-fw mr-r-5"></i>Activités</div>
                <div class="c-tab-item<?php if ($data['page_info']['tab'] == 'bridgers') echo ' active'; ?>" data-index="1"><i class="fas fa-users fa-fw mr-r-5"></i>Bridgeurs</div>
                <div class="c-tab-line"></div>
                <script>
                    $(document).ready(function() {
                        $('.c-tab-line').width($('.c-tab-item.active').innerWidth() + 'px').css({
                            'left': dimNodeListSum($('.c-tab-item.active').prevAll(), 'w', true) + 'px'
                        });
                    });
                </script>
            </div>
            <div class="c-tab-container<?php if ($data['page_info']['tab'] == 'activities') echo ' active'; ?>" data-index="0">
                <?php
                require_once APP_URL . '/views/includes/data/query-activities.php';
                ?>
            </div>
            <div class="c-tab-container<?php if ($data['page_info']['tab'] == 'bridgers') echo ' active'; ?>" data-index="1">
                <?php
                require_once APP_URL . '/views/includes/data/query-bridgers.php';
                ?>
            </div>
        </div>
    </aside>

    <!-- Connected users aside-->
    <aside class="connected-users-aside article i-pd sticky">
        <h5 class="article-title pd-x-5 pd-t-10"><i class="fas fa-link fa-fw"></i><span>Connectés</span></h5>
        <?php
        // Load connected users
        require_once APP_URL . '/views/includes/data/connected-users.php';
        ?>
    </aside>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>