<?php
# DESCRIPTION
/**
 * This is the testimony page to allows to user to tell us something
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main>
    <section id="ask" class="mr-t-30">
        <div class="minimize-x-third mr-x-auto">
            <form class="as-auth-form" action="<?php concat(ROOT_URL, 'data/ask'); ?>" method="POST">
                <!-- Information -->
                <h5 class="title-a center"><?php echo SITE_NAME; ?> fera ses efforts pour répondre à vos questions et les mettres dans la page <a href="<?php concat(ROOT_URL, 'pages/faq'); ?>">FAQ.</a></h5>
                <?php if (isset($_SESSION['ask_flash'])) : ?>
                    <div class="c-form-row">
                        <?php flash('ask_flash'); ?>
                    </div>
                <?php endif; ?>
                <?php if (!isset($_SESSION['user'])) : ?>
                    <!-- Full name -->
                    <div class="c-form-row">
                        <div class="mr-r-10">
                            <label for="first_name">Prénom</label>
                            <input type="text" name="first_name" class="input" placeholder="Votre prénom">
                        </div>

                        <div class="mr-l-10">
                            <label for="last_name">Nom</label>
                            <input type="text" name="last_name" class="input" placeholder="Votre nom">
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Message -->
                <div class="c-form-row">
                    <div>
                        <label for="question">Question</label>
                        <textarea name="question" class="input" placeholder="Bonjour <?php echo SITE_NAME; ?>! J'ai une question c'est..."></textarea>
                    </div>
                </div>
                <button type="submit" class="c-btn c-btn-bridge mr-t-10 pd-x-30">Poser</button>
            </form>
        </div>
    </section>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>

<?php
// Load footer
require_once APP_URL . '/views/includes/footer.php';
?>