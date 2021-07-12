<?php
# DESCRIPTION
/**
 * This is the FAQ page contains Frequencies Asked Questions
 */

// Load header
require_once APP_URL . '/views/includes/header.php';
?>

<!-- Main -->
<main>
    <header class="header-img">
        <div class="minimize-x-lg">
            <div class="light-color">
                <h1>FAQ</h1>
                <p>Frequently Asked Questions ou bien la liste des Questions Fréquemment Posées sur Bridge.</p>
                <a href="<?php concat(ROOT_URL, 'pages/ask'); ?>"><button class="c-btn c-btn-white">Poser une question</button></a>
            </div>
            <img class="mr-l-10" src="<?php getFileURL('004mifp71wmhsqloheu1.svg'); ?>" alt="Image">
        </div>
    </header>

    <section id="faq">
        <div class="minimize-x-md mr-x-auto">
            <div class="search-input-container faq-search-input">
                <input class="transparent-input" type="text" name="search_faq" id="searchFAQ" placeholder="Rechercher une question par mots clés">
            </div>
            <div class="question">
                <div class="c-panel">
                    <div class="c-panel-toggle noselect">
                        <h3>À quoi sert <?php echo SITE_NAME; ?>?</h3>
                        <i class="fas fa-chevron-down fa-fw"></i>
                    </div>
                    <div class="c-panel-container">
                        <p>Bridge est un réseau social qui est utilisé pour connecter des amis en plus de les faire. En plus de publier votre photo, vidéo, audio et texte, vos amis peuvent y réagir et commenter ci-dessous par texte ou audio, et vous pouvez faire de même.</p>
                    </div>
                </div>
            </div>

            <div class="question">
                <div class="c-panel">
                    <div class="c-panel-toggle noselect">
                        <h3>Comment je peux commencer?</h3>
                        <i class="fas fa-chevron-down fa-fw"></i>
                    </div>
                    <div class="c-panel-container">
                        <p>Pour commencer vous pouvez clicker au icon de la personne dans le coin en haut a la droite et appuyer si sur connexion si sur inscription.</p>
                    </div>
                </div>
            </div>

            <div class="question">
                <div class="c-panel">
                    <div class="c-panel-toggle noselect">
                        <h3>Qui sont les createurs du ce reseau social?</h3>
                        <i class="fas fa-chevron-down fa-fw"></i>
                    </div>
                    <div class="c-panel-container">
                        <p>Les createurs sont Hassan et Ayoub, mais pour plus de details visiter [Equipe Bridge] par clicker le button en haut a la droite.</p>
                    </div>
                </div>
            </div>

            <div class="question">
                <div class="c-panel">
                    <div class="c-panel-toggle noselect">
                        <h3>C'est quoi l'icon a cote de mon photo de profile et a quoi sert le text a cote de mon nom?</h3>
                        <i class="fas fa-chevron-down fa-fw"></i>
                    </div>
                    <div class="c-panel-container">
                        <p>L'icon est un badge est il signifie votre role dans cette platform,et le text est le niveau de le role et il peut changer avec votre participatoin au site.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $("#searchFAQ").on("keyup", function() {
                let keywords = $(this).val().toLowerCase();
                $("#faq .question").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(keywords) > -1)
                });
            });
        });
    </script>
</main>
<!-- Ending main (Bottom of nav bar) -->
</main>

<?php
// Load footer
require_once APP_URL . '/views/includes/footer.php';
?>