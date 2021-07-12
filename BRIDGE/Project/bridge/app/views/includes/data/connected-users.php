<div class="search-input-container friend-search-input">
    <input class="transparent-input" type="text" name="search_friend" id="searchConnectedFriend" placeholder="Rechercher">
</div>
<script>
    $(document).ready(function() {
        $("#searchConnectedFriend").on("keyup", function() {
            let keywords = $(this).val().toLowerCase();
            $("#connectedUsers>div").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(keywords) > -1);
            });
        });
    });
</script>

<?php if (count($data['friends']) == 0) : ?>
    <div class="message center mr-y-5">Vous n'avez pas encore d'amis</div>
<?php else : ?>
    <div class="users-list scrollable-list" id="connectedUsers">
        <?php foreach ($data['friends'] as $friend) : ?>
            <div>
                <a class="user" href="<?php concat(ROOT_URL, 'u/' . $friend['username']); ?>">
                    <img class="br" src="<?php getFileURL($friend['main_picture']); ?>" alt="Image">
                    <div class="user-name">
                        <span><?php echo $friend['display_name'] ?></span>
                    </div>
                    <?php if (((int) $friend['state']) == 1) : ?>
                        <i class="fas fa-circle"></i>
                    <?php else : ?>
                        <i style="font-size: .6rem; color: #bababa !important" title="<?php echo getElapsedTime($friend['last_activity_date']); ?>" class="fas fa-clock"></i>
                    <?php endif; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>