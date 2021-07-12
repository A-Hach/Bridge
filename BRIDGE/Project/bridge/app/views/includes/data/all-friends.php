<style>
    .container {
        height: 40%;
        align-content: center;
    }

    .image_outer_container {
        margin-top: auto;
        margin-bottom: auto;
        border-radius: 50%;
        position: relative;
    }

    .image_inner_container {
        border-radius: 50%;
        padding: 3px;
        background: #833ab4;
        background: -webkit-linear-gradient(to bottom, #4548fc, #8e45fc, #ce45fc);
        background: linear-gradient(to bottom, #4548fc, #8e45fc, #ce45fc);
    }

    .image_inner_container img {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        border: 4px solid white;
    }

    .image_outer_container .green_icon {
        background-color: #4cd137;
        position: absolute;
        right: 30px;
        bottom: 10px;
        height: 30px;
        width: 30px;
        border: 5px solid white;
        border-radius: 50%;
    }
</style>
<section>
    <table>
        <?php $row = 0;
        foreach ($data['profile_friends'] as $friendss) : ?>
            <?php $row = $row + 1;
            ?>
            <td style="width:100px">
                <div class="container">
                    <div class="d-flex justify-content-center h-100">
                        <div class="image_outer_container">
                            <div class="image_inner_container">
                                <a href="<?php concat(ROOT_URL, 'u/' .  $friendss['username'] . ''); ?>"><img src="<?php getFileURL($friendss['main_picture']) ?>" title="<?php echo $friendss['display_name']; ?>"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        <?php
            if ($row == 10) {
                $row = 0;
                echo '</td></tr><tr>';
            }
        endforeach; ?>
    </table>
</section>