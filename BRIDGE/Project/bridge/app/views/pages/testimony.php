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
    <section id="testimony" class="mr-t-30">
        <div class="minimize-x-third mr-x-auto">
            <form class="as-auth-form" action="<?php concat(ROOT_URL, 'data/testimony'); ?>" method="POST">
                <!-- Information -->
                <h5 class="title-a center"><?php echo SITE_NAME; ?> sera heureux de vos mots 🤩</h5>
                <?php if (isset($_SESSION['testimony_flash'])) : ?>
                    <div class="c-form-row">
                        <?php flash('testimony_flash'); ?>
                    </div>
                <?php endif; ?>
                <!-- Message -->
                <div class="c-form-row">
                    <div>
                        <label for="message">Message</label>
                        <textarea name="message" class="input" placeholder="J'ai trouvé quelque chose sur <?php echo SITE_NAME; ?>..."></textarea>
                    </div>
                </div>
                <button type="submit" class="c-btn c-btn-bridge mr-t-10 pd-x-30">Envoyer</button>
            </form>
        </div>
    </section>

    <!-- Testimonials section -->
    <section id="testimonials">
        <div class="center minimize-x-md mr-x-auto">
            <div>
                <?php
                // Load testimonials carousel
                require_once APP_URL . '/views/includes/sliders/testimonials-carousel.php';
                ?>
            </div>
            <script>
                let carouselItems = $('#testimonialsCarousel .carousel-item'),
                    maxHeight = carouselItems.eq(0).height();

                // Get the max height
                for (let i = 1; i < carouselItems.length; i++)
                    maxHeight = carouselItems.eq(i).height() > maxHeight ? carouselItems.eq(i).height() : maxHeight;

                // Set each carousel item height as max height
                for (let i = 0; i < carouselItems.length; i++)
                    carouselItems.eq(i).height(maxHeight + "px");
            </script>
        </div>
    </section>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>

<?php
// Load footer
require_once APP_URL . '/views/includes/footer.php';
?>