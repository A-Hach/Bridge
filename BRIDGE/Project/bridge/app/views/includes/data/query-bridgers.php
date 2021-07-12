<div><span class="regular"><?php echo getNumberAbbr(count($data['users'])); ?></span> bridgeur(s) trouvés</div>
<?php foreach ($data['users'] as $user) : ?>
    <a href="<?php concat(ROOT_URL, 'u/' . $user['username']); ?>" class="search-result-user">
        <article>
            <div class="article">
                <div>
                    <div class="sb-c-flex">
                        <div class="fs-c-flex">
                            <img class="normal-d-img" src="<?php getFileURL($user['main_picture']); ?>" alt="Image">
                            <div class="c-fs-col-flex user-info">
                                <h4><?php echo $user['display_name']; ?></h4>
                                <span>@<?php echo $user['username']; ?></span>
                            </div>
                        </div>
                        <div class="sb-c-flex">
                            <span class="mr-r-5"><?php echo $user['name']; ?></span>
                            <img src="<?php getFileURL($user['badge']); ?>" alt="Image" role="icon" title="<?php echo $user['name']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </a>
<?php endforeach; ?>