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
    <!-- Confirm delete account modal -->
    <div class="c-modal" id="confirmDeletingAccount">
        <form method="POST" action="<?php concat(ROOT_URL, 'data/deleteAccount'); ?>">
            <div class="c-modal-inner">
                <img src="<?php getFileURL('ruha4shsm5_158sdqfqsf5.svg'); ?>" alt="Image">
                <p>Êtes-vous sûr de cela? La suppression de votre compte entraîne la suppression de tous vos messages et de toutes vos activités!</p>
                <button type="submit" class="c-btn c-btn-error mr-t-10 pd-x-30">Oui je confirme</button>
            </div>
        </form>
    </div>
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
                <!-- Tab -->
                <div class="c-tab-menu profile-tab-menu">
                    <div class="c-tab-items noselect">
                        <div class="c-tab-item<?php echo $data['page_info']['profile_tab'] == 'activities' ? ' active' : ''; ?>" data-index="0" data-name="activities"><i class="fas fa-clone fa-fw mr-r-5"></i>Activités</div>
                        <div class="c-tab-item<?php echo $data['page_info']['profile_tab'] == 'friends' ? ' active' : ''; ?>" data-index="1" data-name="friends"><i class="fas fa-users fa-fw mr-r-5"></i>Amis (<?php echo getNumberAbbr($data['profile'][0]['total_friends']); ?>)</div>
                        <div class="c-tab-item<?php echo $data['page_info']['profile_tab'] == 'about' ? ' active' : ''; ?>" data-index="2" data-name="about"><i class="fas fa-address-card fa-fw mr-r-5"></i>À propos</div>
                        <?php if ($data['is_my_profile']) : ?>
                            <div class="c-tab-item<?php echo $data['page_info']['profile_tab'] == 'settings' ? ' active' : ''; ?>" data-index="3" data-name="settings"><i class="fas fa-cog fa-fw mr-r-5"></i>Paramètres</div>
                        <?php endif; ?>
                        <div class="c-tab-line"></div>
                        <script>
                            $(document).ready(function() {
                                $('.c-tab-line').width($('.c-tab-item.active').innerWidth() + 'px').css({
                                    'left': dimNodeListSum($('.c-tab-item.active').prevAll(), 'w', true) + 'px'
                                });
                            });
                        </script>
                    </div>
                    <div class="c-tab-container<?php echo $data['page_info']['profile_tab'] == 'activities' ? ' active' : ''; ?>" data-index="0">
                        <?php if (count($data['posts']) == 0) : ?>
                            <div class="message center">Ce compte n'a publié aucune activité</div>
                        <?php else : ?>
                            <?php
                            // Load posts list
                            require_once APP_URL . '/views/includes/data/my-activities.php';
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="c-tab-container<?php echo $data['page_info']['profile_tab'] == 'friends' ? ' active' : ''; ?>" data-index="1">
                        <?php if (count($data['profile_friends']) == 0) : ?>
                            <div class="message center">Vous n'avez aucun ami sur <?php echo SITE_NAME; ?>.</div>
                        <?php else : ?>
                            <?php
                            // Load friends list
                            require_once APP_URL . '/views/includes/data/all-friends.php';
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="c-tab-container<?php echo $data['page_info']['profile_tab'] == 'about' ? ' active' : ''; ?>" data-index="2">
                        <div class="about-categ">
                            <h5>Informations personnelles</h5>
                            <table>
                                <tr>
                                    <td>Prénom:</td>
                                    <td><?php echo $data['profile'][0]['first_name']; ?></td>
                                </tr>
                                <tr>
                                    <td>Nom:</td>
                                    <td><?php echo $data['profile'][0]['last_name']; ?></td>
                                </tr>
                                <?php if (!empty($data['profile'][0]['personnalities'])) : ?>
                                    <tr>
                                        <td>Personnalités:</td>
                                        <td><?php echo $data['profile'][0]['personnalities']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (!empty($data['profile'][0]['sexe'])) : ?>
                                    <tr>
                                        <td>Sexe:</td>
                                        <td><?php echo strtolower($data['profile'][0]['sexe']) == 'male' ? 'Homme' : 'Femme'; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (!empty($data['profile'][0]['bio'])) : ?>
                                    <tr>
                                        <td>Biographie:</td>
                                        <td><?php echo $data['profile'][0]['bio']; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <?php if (count($data['links']) > 0) : ?>
                            <div class="about-categ">
                                <h5>Réseaux Sociaux</h5>
                                <table>
                                    <?php foreach ($data['links'] as $link) : ?>
                                        <tr>
                                            <td>
                                                <img role="md-icon" src="<?php getFileURL($link['favicon']); ?>" alt="Favicon" title="<?php echo $link['name']; ?>">
                                            </td>
                                            <td>
                                                <a target="_blank" href="<?php echo $link['main_url'] . $link['username']; ?>">@<?php echo $link['username']; ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>

                        <div class="about-categ">
                            <h5>Naissance</h5>
                            <table>
                                <?php if (!empty($data['profile'][0]['date_of_birth'])) : ?>
                                    <tr>
                                        <td>Date de naissance:</td>
                                        <td><?php echo date('d/m/Y', strtotime($data['profile'][0]['place_of_birth'])); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if (!empty($data['profile'][0]['place_of_birth'])) : ?>
                                    <tr>
                                        <td>Ville de naissance:</td>
                                        <td><?php echo $data['profile'][0]['place_of_birth']; ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if (!empty($data['profile'][0]['country_id'])) : ?>
                                    <tr>
                                        <td>Nationalité:</td>
                                        <td>
                                            <img style="vertical-align: middle" src="<?php getFileURL($data['profile'][0]['flag']); ?>" role="icon" alt="Flag">
                                            <?php echo $data['profile'][0]['country_name']; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <div class="about-categ">
                            <h5>La vie sur <?php echo SITE_NAME; ?></h5>
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                        $pseudo = 'un Bridgeur';
                                        if (!empty($data['profile'][0]['sexe']))
                                            if (strtolower($data['profile'][0]['sexe']) == 'female')
                                                $pseudo = 'une Bridgeuse';
                                        ?>
                                        <div>Ça fait <b><?php echo strtolower(getElapsedTime($data['profile'][0]['registration_date'], true)); ?></b> et <?php echo $data['profile'][0]['display_name']; ?> est devenu <?php echo $pseudo; ?>!</div>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div><?php echo $data['profile'][0]['display_name']; ?> est <img style="vertical-align: middle" src="<?php getFileURL($data['profile'][0]['badge']); ?>" role="icon" alt="Flag"> <b><?php echo $data['profile'][0]['level_name']; ?></b></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php if ($data['is_my_profile']) : ?>
                        <div class="setting-tab c-tab-container<?php echo $data['page_info']['profile_tab'] == 'settings' ? ' active' : ''; ?>" data-index="3">
                            <div id="registerForm" class="c-tab-container active" data-index="1">
                                <?php if (isset($_SESSION['edit_flash'])) flash('edit_flash'); ?>
                                <form class="as-auth-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
                                    <div class="profile-images">
                                        <div class="main-image-edit">
                                            <img src="<?php getFileURL($data['user'][0]['main_picture']); ?>" alt="Image">
                                            <input type="file" name="main_picture" accept="image/png, image/jpeg" hidden>
                                            <button type="button"><i class="fas fa-cloud-upload-alt"></i></button>
                                        </div>

                                        <div class="cover-image-edit">
                                            <img src="<?php getFileURL($data['user'][0]['cover_picture']); ?>" alt="Image">
                                            <input type="file" name="cover_picture" accept="image/png, image/jpeg" hidden>
                                            <button type="button"><i class="fas fa-cloud-upload-alt"></i></button>
                                        </div>
                                    </div>
                                    <!-- Display name -->
                                    <div class="c-form-row">
                                        <div>
                                            <label class="required" for="display_name">Nom à afficher</label>
                                            <input type="text" name="display_name" value="<?php echo isset($_POST['save']) ? $data['fields']['display_name'] : $data['user'][0]['display_name']; ?>" class="input" placeholder="Votre nom à afficher">
                                            <?php if (!empty($data['errors']['display_name'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['display_name']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Full name -->
                                    <div class="c-form-row">
                                        <div class="mr-r-10">
                                            <label class="required" for="first_name">Prénom</label>
                                            <input type="text" name="first_name" value="<?php echo isset($_POST['save']) ? $data['fields']['first_name'] : $data['user'][0]['first_name']; ?>" class="input" placeholder="Votre prénom">
                                            <?php if (!empty($data['errors']['first_name'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['first_name']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mr-l-10">
                                            <label class="required" for="last_name">Nom</label>
                                            <input type="text" name="last_name" value="<?php echo isset($_POST['save']) ? $data['fields']['last_name'] : $data['user'][0]['last_name']; ?>" class="input" placeholder="Votre nom">
                                            <?php if (!empty($data['errors']['last_name'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['last_name']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Personalities -->
                                    <div class="c-form-row">
                                        <div>
                                            <label for="display_name">Personnalitiés</label>
                                            <input type="text" name="personnalities" value="<?php echo isset($_POST['save']) ? $data['fields']['personnalities'] : $data['user'][0]['personnalities']; ?>" class="input" placeholder="Education, Comedie, Sport">
                                            <?php if (!empty($data['errors']['display_name'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['display_name']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Bio -->
                                    <div class="c-form-row">
                                        <div>
                                            <label for="bio">Bio</label>
                                            <textarea name="bio" class="input" placeholder="Votre biographie"><?php echo isset($_POST['save']) ? $data['fields']['bio'] : $data['user'][0]['bio']; ?></textarea>
                                            <?php if (!empty($data['errors']['bio'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['bio']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Date of birth -->
                                    <label for="" title="Jour de naissance">Date de naissance</label>
                                    <div class="c-form-row">
                                        <div class="mr-r-10">
                                            <?php
                                            $dropdown_dir = 'bf';
                                            $day = isset($_POST['save']) ? $data['fields']['day'] : getFromDate($data['user'][0]['date_of_birth'], 'd');
                                            // Load days drop down
                                            require_once APP_URL . '/views/includes/dropdown/days-dropdown.php';
                                            ?>
                                        </div>

                                        <div class="mr-l-10 mr-r-10">
                                            <?php
                                            $dropdown_dir = 'bf';
                                            $month = isset($_POST['save']) ? $data['fields']['month'] : getFromDate($data['user'][0]['date_of_birth'], 'm');
                                            // Load months drop down
                                            require_once APP_URL . '/views/includes/dropdown/months-dropdown.php';
                                            ?>
                                        </div>

                                        <div class="mr-l-10" title="Année de naissance">
                                            <?php
                                            $dropdown_dir = 'bf';
                                            $year = isset($_POST['save']) ? $data['fields']['year'] : getFromDate($data['user'][0]['date_of_birth'], 'Y');
                                            // Load years drop down
                                            require_once APP_URL . '/views/includes/dropdown/years-dropdown.php';
                                            ?>
                                        </div>
                                    </div>
                                    <!-- Place of birth -->
                                    <div class="c-form-row">
                                        <div class="mr-r-10">
                                            <label for="country">Nationalité</label>
                                            <?php
                                            $dropdown_dir = 'tf';
                                            $country = isset($_POST['save']) ? $data['fields']['country'] : $data['user'][0]['country_name'];
                                            // Load years drop down
                                            require_once APP_URL . '/views/includes/dropdown/countries-dropdown.php';
                                            ?>
                                        </div>

                                        <div class="mr-l-10 mr-r-10">
                                            <label for="city">Ville</label>
                                            <input type="text" name="city" value="<?php echo isset($_POST['save']) ? $data['fields']['city'] : $data['user'][0]['place_of_birth']; ?>" class="input" placeholder="Lieu de naissance">
                                        </div>

                                        <div class="mr-l-10">
                                            <label for="country" class="required">Confidentialité</label>
                                            <?php
                                            $dropdown_dir = 'tf';
                                            $confidentiality = isset($_POST['save']) ? $data['fields']['confidentiality'] : $data['user'][0]['confidentiality'] == 0 ? 'Privé' : 'Publique';
                                            // Load years drop down
                                            require_once APP_URL . '/views/includes/dropdown/confidentiality-dropdown.php';
                                            ?>
                                        </div>
                                    </div>
                                    <!-- Social media -->
                                    <div class="c-form-row">
                                        <div>
                                            <label for="facebook">Facebook</label>
                                            <input type="text" name="facebook" class="input" value="<?php echo isset($_POST['save']) ? $data['fields']['facebook'] : getSocialMedia($data['links'], 'facebook', 'username'); ?>" placeholder="Votre non d'utilisateur">
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="instagram">Instagram</label>
                                            <input type="text" name="instagram" value="<?php echo isset($_POST['save']) ? $data['fields']['instagram'] : getSocialMedia($data['links'], 'instagram', 'username'); ?>" class="input" placeholder="Votre non d'utilisateur">
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="snapchat">Snapchat</label>
                                            <input type="text" value="<?php echo isset($_POST['save']) ? $data['fields']['snapchat'] : getSocialMedia($data['links'], 'snapchat', 'username'); ?>" name="snapchat" class="input" placeholder="Votre non d'utilisateur">
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="linkedin">LinkedIn</label>
                                            <input type="text" value="<?php echo isset($_POST['save']) ? $data['fields']['linkedin'] : getSocialMedia($data['links'], 'linkedin', 'username'); ?>" name="linkedin" class="input" placeholder="Votre non d'utilisateur">
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="youtube">YouTube</label>
                                            <input type="text" value="<?php echo isset($_POST['save']) ? $data['fields']['youtube'] : getSocialMedia($data['links'], 'youtube', 'username'); ?>" name="youtube" class="input" placeholder="Votre non d'utilisateur">
                                        </div>
                                    </div>
                                    <!-- Password & Confirm password -->
                                    <div class="c-form-row">
                                        <div>
                                            <label class="required" for="password">Mot de passe actuel</label>
                                            <div class="iconed-input">
                                                <input type="password" name="password" class="input" placeholder="Votre mot de passe">
                                                <svg class="input-icon togglePassword" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 24 24">
                                                    <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                                                </svg>
                                            </div>
                                            <?php if (!empty($data['errors']['password'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="password">Mot de passe</label>
                                            <div class="iconed-input">
                                                <input type="password" value="" name="new_password" class="input" placeholder="Votre mot de passe">
                                                <svg class="input-icon togglePassword" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 24 24">
                                                    <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                                                </svg>
                                            </div>
                                            <?php if (!empty($data['errors']['new_password'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['new_password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="c-form-row">
                                        <div>
                                            <label for="confirm_password">Confirmer mot de passe</label>
                                            <div class="iconed-input">
                                                <input type="password" value="" name="confirm_new_password" class="input" placeholder="Confirmez votre mot de passe">
                                                <svg class="input-icon togglePassword" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 24 24">
                                                    <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                                                </svg>
                                            </div>
                                            <?php if (!empty($data['errors']['confirm_new_password'])) : ?>
                                                <div class="error-label"><?php echo $data['errors']['confirm_new_password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Sexe -->
                                    <div class="c-form-row fs-c-flex">
                                        <div>
                                            <label for="sexe">Sexe</label>
                                            <div class="fs-c-flex">
                                                <div class="mr-r-10">
                                                    <input <?php if ($data['user'][0]['sexe'] == 'Male') echo 'checked'; ?> type="radio" value="Male" name="sexe">
                                                    <span>Homme</span>
                                                </div>
                                                <div class="mr-l-10">
                                                    <input <?php if ($data['user'][0]['sexe'] == 'Female') echo 'checked'; ?> type="radio" value="Female" name="sexe">
                                                    <span>Femme</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="editProfileButtons">
                                        <button type="submit" name="save" class="c-btn c-btn-success mr-t-10 pd-x-30">Enregistrer</button>
                                        <button type="button" id="confirmDeletingAccountBtn" class="c-btn c-btn-error mr-t-10 pd-x-30">Désactiver mon compte</button>
                                    </div>
                                </form>
                                <script>
                                    $(function() {
                                        $('#confirmDeletingAccountBtn').on('click', function() {
                                            $('#confirmDeletingAccount').addClass('active');
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    <?php endif; ?>
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