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
        <div class="article">
            <h5 class="article-title"><i class="fas fa-id-card fa-fw"></i><span><?php echo $data['profile'][0]['display_name']; ?></span></h5>
            <!-- Cover image -->
            <div class="cover-image">
                <img class="br" src="<?php getFileURL($data['profile'][0]['cover_picture']); ?>" alt="Image">
            </div>
            <div class="general-info">
                <div class="sb-c-flex">
                    <div class="sb-c-flex">
                        <span class="profile-image">
                            <img class="medium-d-img br" src="<?php getFileURL($data['profile'][0]['main_picture']); ?>" alt="Image">
                            <img src="<?php getFileURL($data['profile'][0]['badge']); ?>" alt="Image" role="icon" title=<?php echo $data['profile'][0]['level_name']; ?>>
                        </span>
                        <div class="c-fs-col-flex name">
                            <h2><?php echo $data['profile'][0]['display_name']; ?></h2>
                            <span><span class="regular">@<?php echo $data['profile'][0]['username']; ?></span>, <?php echo $data['profile'][0]['level_name']; ?></span>
                        </div>
                    </div>
                    <div class="sb-c-flex general-stats">
                        <div class="c-c-col-flex">
                            <span class="stat-value"><?php echo getNumberAbbr($data['profile'][0]['total_posts']); ?></span>
                            <span class="stat-name">Activités</span>
                        </div>

                        <div class="c-c-col-flex">
                            <span class="stat-value"><?php echo getNumberAbbr($data['profile'][0]['total_reactions']); ?></span>
                            <span class="stat-name">Flammes</span>
                        </div>

                        <div class="c-c-col-flex">
                            <span class="stat-value"><?php echo getNumberAbbr($data['profile'][0]['level']); ?></span>
                            <span class="stat-name">Niveau</span>
                        </div>
                    </div>
                </div>
                <div class="bio">
                    <p class="bio-content"><?php echo $data['profile'][0]['bio']; ?></p>
                    <!-- Friendship state OR profile owner -->
                    <input type="hidden" value="<?php echo $data['profile'][0]['profile_id']; ?>">
                    <?php if ($data['friendship_state'] == -1) : ?>
                        <button class="c-md-btn c-btn-refuse send-friendship-request"><i class="fas fa-plus fa-fw"></i>Demande d'amitié</button>
                    <?php elseif ($data['friendship_state'] == 0) : ?>
                        <button class="c-md-btn c-btn-refuse destroy-friendship"><i class="fas fa-user-times fa-fw"></i>Détruisez l'amitié</button>
                    <?php elseif ($data['friendship_state'] == 1) : ?>
                        <button class="c-md-btn c-btn-refuse cancel-friendship-request"><i class="fas fa-user-minus fa-fw"></i>Annuler l'amitié</button>
                    <?php elseif ($data['friendship_state'] == 2) : ?>
                        <button class="c-md-btn c-btn-accept confirm-friendship-request"><i class="fas fa-hands-helping fa-fw"></i>Confirmer l'amitié</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="pd-x-15">
                <div class="alert">
                    <img class="image" src="<?php getFileURL('Y9ag5wgWapnFffs4Xhg.svg'); ?>" alt="Image">
                    <div class="text">La page de <?php echo $data['profile'][0]['display_name']; ?> est privée!</div>
                    <p>Envoyer une demande d'amitié pour voir les activités de cette page.</p>
                </div>
            </div>
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