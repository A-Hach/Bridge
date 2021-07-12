<?php
# DESCRIPTION
/**
 * This is the FAQ page contains Frequencies Asked Questions
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
    <aside class="last-activities-aside">
        <?php if (count($data['top_friends_posts']) > 1) : ?>
            <!-- Trend article -->
            <div class="week-trend article">
                <h5 class="article-title"><i class="fas fa-fire fa-fw"></i><span>Tendance</span></h5>
                <h5 class="title-b">Les photos les plus flammées de vous et vos amis.</h5>
                <div class="center">
                    <?php
                    require_once APP_URL . '/views/includes/sliders/week-trend-slider.php';
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Add article buttons -->
        <div class="add-activities article">
            <h5 class="article-title"><i class="fas fa-plus-circle fa-fw"></i><span>Ajouter une activité</span></h5>
            <div class="buttons">
                <button class="c-btn sides-radius add-text-btn" id="addTextBtn">
                    <svg width="22" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 22C15.5228 22 20 17.5228 20 12C20 6.47715 15.5228 2 10 2C4.47715 2 0 6.47715 0 12C0 17.5228 4.47715 22 10 22Z" fill="none" />
                        <path d="M4.54547 7.45453V11.7403H7.66235C7.66235 13.3156 6.26409 14.5973 4.54547 14.5973V16.026C7.12361 16.026 9.22084 14.1035 9.22084 11.7403V7.45453H4.54547Z" fill="#777777" />
                        <path d="M10.9091 11.7403H14.026C14.026 13.3156 12.6278 14.5973 10.9091 14.5973V16.026C13.4872 16.026 15.5845 14.1035 15.5845 11.7403V7.45453H10.9091V11.7403Z" fill="#777777" />
                    </svg>
                    <span>Texte</span>
                </button>
                <button class="c-btn sides-radius add-media-btn" id="addImageBtn" data-accept="image/png, image/jpeg">
                    <svg width="22" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 22C15.5228 22 20 17.5228 20 12C20 6.47715 15.5228 2 10 2C4.47715 2 0 6.47715 0 12C0 17.5228 4.47715 22 10 22Z" fill="none" />
                        <path d="M14.0908 7.45453H5.90904C5.15748 7.45453 4.54544 8.05326 4.54544 8.78857V14.6581C4.54544 15.3933 5.15748 15.9921 5.90904 15.9921H14.0908C14.8425 15.9921 15.4545 15.3933 15.4545 14.6581V8.78857C15.4545 8.05326 14.8425 7.45453 14.0908 7.45453ZM5.90904 8.52175H14.0908C14.2414 8.52175 14.3636 8.64124 14.3636 8.78857V12.5765L12.6405 10.6097C12.4578 10.4 12.1932 10.2879 11.9091 10.2816C11.6265 10.2831 11.3614 10.4059 11.1803 10.6183L9.15453 12.997L8.49451 12.3529C8.12148 11.988 7.51434 11.988 7.1418 12.3529L5.63638 13.8251V8.78857C5.63638 8.64124 5.75853 8.52175 5.90904 8.52175Z" fill="#128FE2" />
                    </svg>
                    <span>Photo</span>
                </button>
                <button class="c-btn sides-radius add-media-btn" id="addVideoBtn" data-accept="video/mp4">
                    <svg width="22" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 22C15.5228 22 20 17.5228 20 12C20 6.47715 15.5228 2 10 2C4.47715 2 0 6.47715 0 12C0 17.5228 4.47715 22 10 22Z" fill="none" />
                        <path d="M11.5886 8.74243H5.30304C4.8848 8.74292 4.54596 9.08176 4.54547 9.5V14.4854C4.54596 14.9036 4.8848 15.2425 5.30304 15.243H11.5886C12.0067 15.2425 12.3457 14.9036 12.3461 14.4854V9.5C12.3457 9.08176 12.0067 8.74292 11.5886 8.74243Z" fill="#F4882F" />
                        <path d="M12.8512 13.0303L15.4546 14.4516V9.54999L12.8512 10.9713V13.0303Z" fill="#F4882F" />
                    </svg>
                    <span>Vidéo</span>
                </button>
                <button class="c-btn sides-radius add-media-btn" id="addAudioBtn" data-accept="audio/mp3">
                    <svg width="22" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 22C15.5228 22 20 17.5228 20 12C20 6.47715 15.5228 2 10 2C4.47715 2 0 6.47715 0 12C0 17.5228 4.47715 22 10 22Z" fill="none" />
                        <path d="M13.3891 12.2149C13.3891 11.9954 13.2212 11.8275 13.0016 11.8275C12.7821 11.8275 12.6143 11.9954 12.6143 12.2149C12.6143 13.7259 11.3874 14.9528 9.87638 14.9528C8.36538 14.9528 7.13851 13.7259 7.13851 12.2149C7.13851 11.9954 6.97063 11.8275 6.75108 11.8275C6.53154 11.8275 6.36365 11.9954 6.36365 12.2149C6.36365 14.01 7.70675 15.521 9.48892 15.7147V16.7479H8.08127C7.86172 16.7479 7.69384 16.9158 7.69384 17.1354C7.69384 17.3549 7.86172 17.5227 8.08127 17.5227H11.6715C11.891 17.5227 12.0589 17.3549 12.0589 17.1354C12.0589 16.9158 11.891 16.7479 11.6715 16.7479H10.2638V15.7147C12.046 15.521 13.3891 14.01 13.3891 12.2149Z" fill="#EC3BBB" />
                        <path d="M9.87637 6.54547C8.68826 6.54547 7.71967 7.51405 7.71967 8.70218V12.202C7.71967 13.403 8.68826 14.3587 9.87637 14.3717C11.0646 14.3717 12.0331 13.403 12.0331 12.2149V8.70218C12.0331 7.51405 11.0646 6.54547 9.87637 6.54547Z" fill="#EC3BBB" />
                    </svg>
                    <span>Audio</span>
                </button>
            </div>
        </div>
        <!-- Modals to add articles -->
        <div class="c-modal" id="addMediaModal">
            <div class="c-modal-inner">
                <form class="as-auth-form" action="<?php concat(ROOT_URL, 'home/media'); ?>" method="post" enctype="multipart/form-data">
                    <!-- Texte -->
                    <div class="c-form-row">
                        <div>
                            <textarea name="text" id="mediaTextToAdd" class="input" placeholder="Écrivez un texte ici" spellcheck="false"></textarea>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="c-form-row media-input">
                        <div class="mr-r-10">
                            <input id="mediaInput" type="file" name="media" hidden data-to="mediaLabel">
                            <button type="button" class="c-btn c-btn-success" role="file-button" data-input="mediaInput"><i class="fas fa-cloud-upload-alt mr-r-5"></i>Télécharger</button>
                        </div>

                        <div class="mr-l-10 fs-c-flex">
                            <label id="mediaLabel"></label>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="c-form-row">
                        <div>
                            <label for="text">Mots clés</label>
                            <input class="input" id="mediaTagsToAdd" type="text" name="tags" placeholder="Comédie, éducation, sport">
                        </div>
                    </div>
                    <button type="submit" class="c-btn c-btn-bridge mr-t-10 pd-x-30">Publier</button>
                </form>
            </div>
        </div>

        <div class="c-modal" id="addTextModal">
            <div class="c-modal-inner">
                <form class="as-auth-form" action="<?php concat(ROOT_URL, 'home/text'); ?>" method="post">
                    <!-- Texte -->
                    <div class="c-form-row">
                        <div>
                            <textarea name="text" id="textToAdd" class="input" placeholder="Écrivez un texte ici" spellcheck="false"></textarea>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="c-form-row">
                        <div>
                            <label for="text">Mots clés</label>
                            <input class="input" id="tagsToAdd" type="text" name="tags" placeholder="Comédie, éducation, sport">
                        </div>
                    </div>
                    <button type="submit" class="c-btn c-btn-bridge mr-t-10 pd-x-30">Publier</button>
                </form>
            </div>
            <script>
                $(document).ready(function() {
                    $('.add-media-btn').on('click', function() {
                        $('#addMediaModal').addClass('active').find('#mediaInput').attr('accept', $(this).attr('data-accept'));
                    });

                    $('.add-text-btn').on('click', function() {
                        $('#addTextModal').addClass('active');
                    });
                });
            </script>
        </div>

        <!-- All posts -->
        <?php
        require_once APP_URL . '/views/includes/data/all-activities.php';
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