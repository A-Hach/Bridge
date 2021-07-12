<?php
// Load footer
require_once APP_URL . '/views/includes/footer/' . $data['page_info']['footer'] . '.php';
?>

<!-- Ending main page content -->

<!-- Functions -->
<script src="<?php concat(ROOT_URL, 'js/functions.js'); ?>"></script>
<!-- P5 JS -->
<script src="<?php concat(ROOT_URL, 'scripts/p5.min.js'); ?>"></script>
<!-- P5 Sound JS -->
<script src="<?php concat(ROOT_URL, 'scripts/p5.sound.min.js'); ?>"></script>
<!-- Main JS -->
<script src="<?php concat(ROOT_URL, 'js/main.js'); ?>"></script>
</body>

</html>