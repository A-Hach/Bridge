<div class="center profile-data article">
    <span class="image">
        <img src="<?php getFileURL($data['user'][0]['main_picture']) ?>" alt="Image">
        <img src="<?php getFileURL($data['user'][0]['badge']); ?>" alt="Image" role="icon" title="<?php echo $data['user'][0]['level_name']; ?>">
    </span>
    <div class="name">
        <a href="<?php concat(ROOT_URL, 'u/' . $data['user'][0]['username']); ?>">
            <a href="<?php concat(ROOT_URL, 'u/' . $data['user'][0]['username']); ?>">
                <h3><?php echo $data['user'][0]['display_name']; ?></h3>
            </a>
        </a>
        <small>@<?php echo $data['user'][0]['username']; ?></small>
    </div>
    <p class="bio"><?php echo $data['user'][0]['bio']; ?></p>
</div>