<?php
# DESCRIPTION
/**
 * This is the welcome page
 * Testimonials
 * Features
 * Statistiques
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main>
    <header class="header-img">
        <div class="minimize-x-lg">
            <div class="light-color">
                <h1>Restez en contact</h1>
                <p><?php echo DESCRIPTION; ?></p>
                <a href="<?php concat(ROOT_URL, !isset($_SESSION['user']) ? 'auth' : 'home'); ?>">
                    <button class="c-btn c-btn-white">
                        <?php
                        if (!isset($_SESSION['user']))
                            echo 'Commencez votre activité';
                        else
                            echo 'Aller à @' . $_SESSION['username'];
                        ?>
                    </button>
                </a>
            </div>
            <img class="mr-l-10" src="<?php getFileURL('pbuteg1m4k32qbjigszv.svg'); ?>" alt="Image">
        </div>
    </header>

    <!-- Stats Section -->
    <section id="stats">
        <div class="sb-c-flex minimize-x-md mr-x-auto">
            <img src="<?php getFileURL('68fluh25k6o6h56e7now.svg'); ?>" alt="Image">
            <div class="mr-l-30">
                <h2><?php echo $data['members_count']; ?> membres</h2>
                <p>Il y a <?php echo $data['members_count']; ?> personnes qui utilisent Bridge pour contacter avec leurs amis, et partager des photos, vidéos et des idées...</p>
                <p><strong>Qu’attendez-vous? Rejoignez-les maintenant!</strong></p>
                <a href="<?php concat(ROOT_URL, !isset($_SESSION['user']) ? 'auth' : 'home'); ?>"><button class="c-btn c-btn-bridge">
                        <?php
                        if (!isset($_SESSION['user']))
                            echo 'Joindre';
                        else
                            echo 'Aller à @' . $_SESSION['username'];
                        ?>
                    </button></a>
            </div>
        </div>
    </section>

    <!-- Last Ten Days Section -->
    <section id="lastTenDays">
        <div class="center minimize-x-md mr-x-auto">
            <h2 class="section-title">Ces dernier 10 jours</h2>
            <p>Au cours des 10 derniers jours, de nombreux membres nous ont rejoints et utilisent nos services!</p>
            <canvas id="membersJoinedLastTenDays" class="section-container"></canvas>
        </div>
    </section>

    <!-- Features section -->
    <section id="features">
        <div class="center minimize-x-md mr-x-auto">
            <h2 class="section-title">Fonctionnalités</h2>
            <div class="grid normal-grid section-container">
                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('khp2ya5nzw5u22in0rk7.svg'); ?>" alt="Image"></div>
                    <h3>Mise à niveau</h3>
                    <p>Au moment d’utiliser Bridge et de faire plus en plus d’acitivités, vous obtiendrez un badge selon l’état de votre activités.</p>
                </div>

                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('0zvprjrngttmyxohluoo.svg'); ?>" alt="Image"></div>
                    <h3>Communication</h3>
                    <p>Avec Bridge et en temps réel, vous pouvez messager vos amis le plus rapidement possible!</p>
                </div>

                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('r0kr2025gfwb2rocy6y3.svg'); ?>" alt="Image"></div>
                    <h3>Sécurité</h3>
                    <p>Bridge protège toutes vos activités et les informations personnelles.</p>
                </div>

                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('364mef971umhrqloheu1.svg'); ?>" alt="Image"></div>
                    <h3>Media</h3>
                    <p>Bridge vous permet de postez que vous voulez (Image, Text, Audio...) il n’est pas important quelle media vous souhaitez.</p>
                </div>

                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('hhuawft36lzf84674giy.svg'); ?>" alt="Image"></div>
                    <h3>Témoignages</h3>
                    <p>Vous pouvez également nous donner votre avis sur nos services et nous les partagerons dans la section des <a href="#testimonials">témoignages</a>.</p>
                </div>

                <div>
                    <div class="mr-b-15"><img src="<?php getFileURL('udttw6euwqg2lhuuvd20.svg'); ?>" alt="Image"></div>
                    <h3>Statistiques</h3>
                    <p>Au moment d’utiliser Bridge et de faire plus en plus d’acitivités, vous obtiendrez un badge selon l’état de votre activités.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials section -->
    <section id="testimonials">
        <div class="center minimize-x-md mr-x-auto">
            <h2 class="section-title">Ce que nos clients disent</h2>
            <div>
                <?php
                // Load testimonials carousel
                require_once APP_URL . '/views/includes/sliders/testimonials-carousel.php';
                ?>
                <a href="<?php concat(ROOT_URL, 'pages/testimony'); ?>"><button class="c-btn c-btn-bridge">Je veux donner mon avis</button></a>
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