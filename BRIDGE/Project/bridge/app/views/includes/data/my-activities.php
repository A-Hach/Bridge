<div class="my-activities-grid">
    <?php foreach ($data['posts'] as $post) : ?>
        <article <?php if ($post['media_type'] == 'audio') echo 'class="audio"'; ?>>
            <a href="<?php concat(ROOT_URL, 'activities/' . $post['id']); ?>">
                <?php if ($post['media_type'] == 'audio') : ?>
                    <div class="overlay"></div>
                    <img class="activity-icon" role="lg-icon" src="<?php getFileURL('audio.svg'); ?>">
                <?php elseif ($post['media_type'] == 'image') : ?>
                    <img src="<?php getFileURL($post['media']); ?>">
                    <div class="overlay"></div>
                    <img class="activity-icon" role="lg-icon" src="<?php getFileURL('image.svg'); ?>">
                <?php elseif ($post['media_type'] == 'video') : ?>
                    <video class="no-media-effect" src="<?php getFileURL($post['media']); ?>"></video>
                    <div class="overlay"></div>
                    <img class="activity-icon" role="lg-icon" src="<?php getFileURL('video.svg'); ?>">
                <?php else : ?>
                    <p class="my-text-activity"><?php echo $post['text']; ?></p>
                    <div class="overlay"></div>
                    <img class="activity-icon" role="lg-icon" src="<?php getFileURL('text.svg'); ?>">
                <?php endif; ?>
            </a>
        </article>
    <?php endforeach; ?>
</div>