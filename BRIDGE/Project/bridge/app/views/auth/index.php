<?php
# DESCRIPTION
/**
 * This is the auth page contains Register & Login form
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main>
    <section id="auth">
        <div class="minimize-x-third mr-x-auto">
            <div class="c-tab-menu">
                <div class="c-tab-items noselect">
                    <div class="c-tab-item<?php if ($data['page_info']['tab'] == 'login') echo ' active'; ?>" data-index="0">Se Connecter</div>
                    <div class="c-tab-item<?php if ($data['page_info']['tab'] == 'register') echo ' active'; ?>" data-index="1">Créer un nouveau compte</div>
                    <div class="c-tab-line"></div>
                    <script>
                        $(document).ready(function() {
                            $('.c-tab-line').width($('.c-tab-item.active').innerWidth() + 'px').css({
                                'left': dimNodeListSum($('.c-tab-item.active').prevAll(), 'w', true) + 'px'
                            });
                        });
                    </script>
                </div>
                <div id="loginForm" class="c-tab-container<?php if ($data['page_info']['tab'] == 'login') echo ' active'; ?>" data-index="0">
                    <?php if (isset($_SESSION['login_flash'])) flash('login_flash'); ?>
                    <form class="as-auth-form" action="<?php concat(ROOT_URL, 'auth?tab=login'); ?>" method="POST">
                        <!-- Username -->
                        <div class="c-form-row">
                            <div>
                                <label for="id">Identifiant</label>
                                <input type="text" name="id" class="input" placeholder="Votre nom d'utilisateur ou adresse e-mail">
                                <?php if (!empty($data['login_errors']['login'])) : ?>
                                    <div class="error-label"><?php echo $data['login_errors']['login']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="c-form-row">
                            <div>
                                <label for="login_password">Mot de passe</label>
                                <div class="iconed-input">
                                    <input type="password" name="login_password" class="input" placeholder="Votre mot de passe">
                                    <svg class="input-icon togglePassword" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 24 24">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                                    </svg>
                                </div>
                                <?php if (!empty($data['login_errors']['password'])) : ?>
                                    <div class="error-label"><?php echo $data['login_errors']['password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" name="login" class="c-btn c-btn-success mr-t-10 pd-x-30">Se Connecter</button>
                    </form>
                </div>
                <div id="registerForm" class="c-tab-container<?php if ($data['page_info']['tab'] == 'register') echo ' active'; ?>" data-index="1">
                    <?php if (isset($_SESSION['register_flash'])) flash('register_flash'); ?>
                    <form class="as-auth-form" action="<?php concat(ROOT_URL, 'auth?tab=register'); ?>" method="POST">
                        <!-- Full name -->
                        <div class="c-form-row">
                            <div class="mr-r-10">
                                <label class="required" for="first_name">Prénom</label>
                                <input type="text" name="first_name" value="<?php echo $data['fields']['first_name']; ?>" class="input" placeholder="Votre prénom">
                                <?php if (!empty($data['errors']['first_name'])) : ?>
                                    <div class="error-label"><?php echo $data['errors']['first_name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mr-l-10">
                                <label class="required" for="last_name">Nom</label>
                                <input type="text" name="last_name" value="<?php echo $data['fields']['last_name']; ?>" class="input" placeholder="Votre nom">
                                <?php if (!empty($data['errors']['last_name'])) : ?>
                                    <div class="error-label"><?php echo $data['errors']['last_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Username -->
                        <div class="c-form-row">
                            <div>
                                <label class="required" for="username">Identifiant</label>
                                <input type="text" name="username" value="<?php echo $data['fields']['username']; ?>" class="input" placeholder="Votre nom d'utilisateur">
                                <?php if (!empty($data['errors']['username'])) : ?>
                                    <div class="error-label"><?php echo $data['errors']['username']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="c-form-row">
                            <div>
                                <label class="required" for="email">E-mail</label>
                                <input type="text" name="email" value="<?php echo $data['fields']['email']; ?>" class="input" placeholder="Votre adresse e-mail">
                                <?php if (!empty($data['errors']['email'])) : ?>
                                    <div class="error-label"><?php echo $data['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Date of birth -->
                        <label for="" title="Jour de naissance">Date de naissance</label>
                        <div class="c-form-row">
                            <div class="mr-r-10">
                                <?php
                                $dropdown_dir = 'bf';
                                // Load days drop down
                                require_once APP_URL . '/views/includes/dropdown/days-dropdown.php';
                                ?>
                            </div>

                            <div class="mr-l-10 mr-r-10">
                                <?php
                                $dropdown_dir = 'bf';
                                // Load months drop down
                                require_once APP_URL . '/views/includes/dropdown/months-dropdown.php';
                                ?>
                            </div>

                            <div class="mr-l-10" title="Année de naissance">
                                <?php
                                $dropdown_dir = 'bf';
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
                                $country = $data['fields']['country'];
                                // Load years drop down
                                require_once APP_URL . '/views/includes/dropdown/countries-dropdown.php';
                                ?>
                            </div>

                            <div class="mr-l-10">
                                <label for="city">Ville</label>
                                <input type="text" name="city" value="<?php echo $data['fields']['city']; ?>" class="input" placeholder="Lieu de naissance">
                            </div>
                        </div>
                        <!-- Password & Confirm password -->
                        <div class="c-form-row">
                            <div>
                                <label class="required" for="password">Mot de passe</label>
                                <div class="iconed-input">
                                    <input type="password" value="<?php echo $data['fields']['password']; ?>" name="password" class="input" placeholder="Votre mot de passe">
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
                                <label class="required" for="confirm_password">Confirmer mot de passe</label>
                                <div class="iconed-input">
                                    <input type="password" value="<?php echo $data['fields']['confirm_password']; ?>" name="confirm_password" class="input" placeholder="Confirmez votre mot de passe">
                                    <svg class="input-icon togglePassword" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 24 24">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                                    </svg>
                                </div>
                                <?php if (!empty($data['errors']['confirm_password'])) : ?>
                                    <div class="error-label"><?php echo $data['errors']['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Sexe -->
                        <div class="c-form-row fs-c-flex">
                            <div>
                                <label for="sexe">Sexe</label>
                                <div class="fs-c-flex">
                                    <div class="mr-r-10">
                                        <input type="radio" value="Male" name="sexe">
                                        <span>Homme</span>
                                    </div>
                                    <div class="mr-l-10">
                                        <input type="radio" value="Female" name="sexe">
                                        <span>Femme</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="register" class="c-btn c-btn-success mr-t-10 pd-x-30">S'Inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>

<?php
// Load footer
require_once APP_URL . '/views/includes/footer.php';
?>