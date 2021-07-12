<div>
    <?php if (count($data['lovely_posts']) == 0) : ?>
        <div class="message center mr-t-5">Vous n'avez aucune réaction sur <?php echo SITE_NAME; ?></div>
    <?php else : ?>
        <?php foreach ($data['lovely_posts'] as $post) : ?>
            <article>
                <?php if ($post['media_type'] == 'image') : ?>
                    <div class="activity-media">
                        <img class="f-width" src="<?php getFileURL($post['media']); ?>" alt="Image">
                    </div>
                <?php elseif ($post['media_type'] == 'video') : ?>
                    <div class="activity-media">
                        <video class="f-width" src="<?php getFileURL($post['media']); ?>" controls></video>
                    </div>
                <?php elseif ($post['media_type'] == 'audio') : ?>
                    <div class="activity-media">
                        <audio class="f-width" src="<?php getFileURL($post['media']); ?>" controls></audio>
                    </div>
                <?php endif; ?>
                <div class="sb-c-flex">
                    <div class="fs-c-flex">
                        <!-- Flames -->
                        <svg role="sm-icon" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11" cy="11" r="11" fill="url(#78t78r4f3dgfdfgd_linear)" />
                            <path d="M15.9715 11.6113C15.8188 9.67252 14.8929 8.45755 14.0762 7.3854C13.3199 6.39284 12.6667 5.5357 12.6667 4.2713C12.6667 4.16973 12.6083 4.07691 12.5158 4.03037C12.423 3.98355 12.3116 3.99121 12.2272 4.05098C11.0005 4.9068 9.97706 6.34921 9.61953 7.72549C9.37133 8.68365 9.33849 9.76082 9.33388 10.4722C8.20107 10.2363 7.94445 8.58422 7.94174 8.56622C7.92898 8.48053 7.87529 8.40596 7.79716 8.36523C7.71823 8.32504 7.62544 8.32212 7.54542 8.36073C7.48602 8.38877 6.08734 9.08168 6.00596 11.8483C6.00026 11.9403 6 12.0326 6 12.1249C6 14.8127 8.2431 16.9996 11 16.9996C11.0038 16.9998 11.0079 17.0004 11.0111 16.9996C11.0122 16.9996 11.0133 16.9996 11.0146 16.9996C13.7648 16.9919 16 14.8079 16 12.1249C16 11.9898 15.9715 11.6113 15.9715 11.6113ZM11 16.458C10.0809 16.458 9.33333 15.6815 9.33333 14.727C9.33333 14.6945 9.33307 14.6617 9.33549 14.6215C9.34661 14.219 9.42503 13.9442 9.51102 13.7614C9.67216 14.0989 9.96023 14.4091 10.4282 14.4091C10.5817 14.4091 10.706 14.288 10.706 14.1383C10.706 13.7527 10.7141 13.3079 10.8126 12.9064C10.9002 12.5504 11.1096 12.1717 11.3749 11.8681C11.4929 12.2622 11.723 12.5811 11.9476 12.8924C12.269 13.3378 12.6013 13.7982 12.6597 14.5834C12.6632 14.63 12.6667 14.6768 12.6667 14.727C12.6667 15.6815 11.9191 16.458 11 16.458Z" fill="#FAFAFA" />
                            <defs>
                                <linearGradient id="78t78r4f3dgfdfgd_linear" x1="15.5" y1="20.5" x2="8.5" y2="3.5" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#D60C0C" />
                                    <stop offset="1" stop-color="#FF542F" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <small class="act-value"><?php echo getNumberAbbr($post['total_reactions']); ?></small>
                        <span class="gray-bubble"></span>
                        <!-- Commments -->
                        <svg role="sm-icon" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11" cy="11" r="11" fill="url(#ypyotlgh8h45fg2h_linear)" />
                            <path d="M15.2816 6.79756C14.108 5.60992 12.4962 4.95954 10.8279 5.00195C7.50528 5.10092 4.90376 7.85797 5.00273 11.1806C5.01687 11.8734 5.15826 12.552 5.41275 13.1882C5.62483 13.7255 5.90761 14.2204 6.26107 14.6728L5.76622 15.8463C5.56828 16.2988 5.78036 16.8219 6.2328 17.0198C6.38832 17.0905 6.57213 17.1047 6.74179 17.0764L9.03226 16.6805C9.8523 16.9633 10.7148 17.0622 11.5772 16.9774C14.5322 16.6805 16.8368 14.2628 16.9923 11.2937C17.0772 9.61116 16.4551 7.98521 15.2816 6.79756ZM10.5592 13.4003H8.98984C8.74948 13.4003 8.5374 13.2024 8.5374 12.9479C8.5374 12.7075 8.73534 12.4955 8.98984 12.4955H10.5592C10.7996 12.4955 11.0117 12.6934 11.0117 12.9479C11.0117 13.2024 10.8137 13.4003 10.5592 13.4003ZM13.0052 11.5623H8.98984C8.74948 11.5623 8.5374 11.3644 8.5374 11.1099C8.5374 10.8554 8.73534 10.6574 8.98984 10.6574H13.0052C13.2456 10.6574 13.4577 10.8554 13.4577 11.1099C13.4577 11.3644 13.2456 11.5623 13.0052 11.5623ZM13.0052 9.71013H8.98984C8.74948 9.71013 8.5374 9.51219 8.5374 9.25769C8.5374 9.01734 8.73534 8.80526 8.98984 8.80526H13.0052C13.2456 8.80526 13.4577 9.0032 13.4577 9.25769C13.4577 9.49805 13.2456 9.71013 13.0052 9.71013Z" fill="#FAFAFA" />
                            <defs>
                                <linearGradient id="ypyotlgh8h45fg2h_linear" x1="15.5" y1="20.5" x2="8.5" y2="3.5" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#39D020" />
                                    <stop offset="1" stop-color="#77FF61" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <small class="act-value"><?php echo getNumberAbbr($post['total_comments']); ?></small>
                    </div>
                    <div class="c-dropdown" id="userMainDropdown" data-for="small-articles">
                        <span class="c-dropdown-toggle">
                            <i role="sm-icon" class="fas fa-ellipsis-h"></i>
                        </span>
                        <ul class="c-dropdown-items tl-dropdown c-scrollable">
                            <li><a href="<?php concat(ROOT_URL, 'activities/' . $post['id']); ?>" class="pd-y-5 pd-x-10 link"><i class="fas fa-eye fa-fw mr-r-10"></i>Afficher</a></li>
                        </ul>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>