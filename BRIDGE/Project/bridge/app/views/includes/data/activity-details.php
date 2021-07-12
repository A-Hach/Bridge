<div class="activities-list">
    <!-- Activity -->
    <article>
        <div class="article">
            <div class="activity-author">
                <div class="fs-c-flex">
                    <img class="br mr-r-5" src="<?php getFileURL($data['post']['main_picture']); ?>" alt="Image">
                    <div class="c-fs-col-flex">
                        <a href="<?php concat(ROOT_URL, 'u/' . $data['post']['username']); ?>" class="username link">@<?php echo $data['post']['username']; ?></a>
                        <small class="elapsed-time"><?php echo getElapsedTime($data['post']['posting_date']); ?></small>
                    </div>
                </div>
                <div class="c-dropdown noselect">
                    <div class="c-dropdown-toggle"><i class="fas fa-ellipsis-h fa-fw"></i></div>
                    <ul class="c-dropdown-items bl-dropdown">
                        <input type="hidden" value="<?php echo $data['post']['post_id']; ?>" class="activity-id">
                        <?php if ($data['post']['user_id'] == $_SESSION['user']) : ?>
                            <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link save-btn"><i class="fas fa-bookmark fa-fw mr-r-10"></i>À regarder plus tard</a></li>
                            <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link edit-btn"><i class="fas fa-highlighter fa-fw mr-r-10"></i>Modifier</a></li>
                            <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link delete-btn"><i class="fas fa-trash fa-fw mr-r-10"></i>Supprimer</a></li>
                        <?php else : ?>
                            <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link save-btn"><i class="fas fa-bookmark fa-fw mr-r-10"></i>À regarder plus tard</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="activity-text"><?php echo $data['post']['text']; ?></div>
            <?php if ($data['post']['media_type'] == 'image') : ?>
                <div class="activity-media">
                    <img class="f-width" src="<?php getFileURL($data['post']['media']); ?>" alt="Image">
                </div>
            <?php elseif ($data['post']['media_type'] == 'video') : ?>
                <div class="activity-media">
                    <video class="f-width" src="<?php getFileURL($data['post']['media']); ?>" controls></video>
                </div>
            <?php elseif ($data['post']['media_type'] == 'audio') : ?>
                <div class="activity-media">
                    <audio class="f-width" src="<?php getFileURL($data['post']['media']); ?>" controls></audio>
                </div>
            <?php endif; ?>
            <div class="activity-actions">
                <div class="fs-c-flex">
                    <input type="hidden" value="<?php echo $data['post']['post_id']; ?>">
                    <button class="like-btn<?php echo $data['post']['liked'] == 1 ? ' liked' : ''; ?>">
                        <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.9573 10.5387C14.7282 7.85426 13.3393 6.17199 12.1143 4.68747C10.9798 3.31316 10.0001 2.12635 10.0001 0.375644C10.0001 0.235008 9.91245 0.106489 9.7737 0.0420487C9.6345 -0.0227789 9.4674 -0.012173 9.3408 0.0705855C7.50075 1.25557 5.96559 3.25275 5.42929 5.15837C5.05699 6.48505 5.00774 7.97652 5.00082 8.9615C3.30161 8.63487 2.91668 6.34738 2.91261 6.32246C2.89347 6.20381 2.81294 6.10056 2.69574 6.04416C2.57735 5.98851 2.43816 5.98447 2.31813 6.03793C2.22903 6.07676 0.13101 7.03617 0.00893998 10.8669C0.000389981 10.9943 0 11.1221 0 11.2499C0 14.9714 3.36465 17.9994 7.5 17.9994C7.5057 17.9997 7.51185 18.0005 7.51665 17.9994C7.5183 17.9994 7.51995 17.9994 7.5219 17.9994C11.6472 17.9888 15 14.9648 15 11.2499C15 11.0628 14.9573 10.5387 14.9573 10.5387ZM7.5 17.2495C6.12135 17.2495 5 16.1744 5 14.8528C5 14.8078 4.99961 14.7623 5.00324 14.7067C5.01992 14.1494 5.13754 13.7689 5.26653 13.5158C5.50824 13.9831 5.94034 14.4126 6.6423 14.4126C6.87255 14.4126 7.059 14.2449 7.059 14.0376C7.059 13.5037 7.07115 12.8879 7.2189 12.3319C7.3503 11.839 7.6644 11.3147 8.06235 10.8943C8.23935 11.44 8.5845 11.8815 8.9214 12.3125C9.4035 12.9293 9.90195 13.5667 9.98955 14.6539C9.9948 14.7185 10.0001 14.7833 10.0001 14.8528C10.0001 16.1744 8.87865 17.2495 7.5 17.2495Z" fill="#888888" />
                        </svg>
                    </button>
                    <span class="reactions-value"><?php echo $data['post']['liked'] == 1 ? 'Vous et ' . getNumberAbbr($data['post']['total_reactions'] - 1) : getNumberAbbr($data['post']['total_reactions']); ?> personnes</span>
                    <span class="gray-bubble"></span>
                    <button>
                        <svg width="16" height="16" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.4224 2.67626C13.662 0.908068 11.2443 -0.0602355 8.74187 0.00290562C3.75793 0.150255 -0.144356 4.25503 0.004099 9.20184C0.025309 10.2333 0.237394 11.2436 0.61913 12.1908C0.93725 12.9908 1.36142 13.7276 1.89161 14.4011L1.14934 16.1483C0.852425 16.822 1.17055 17.6008 1.84921 17.8954C2.08249 18.0007 2.3582 18.0218 2.61269 17.9797L6.0484 17.3902C7.27846 17.8113 8.57222 17.9585 9.86582 17.8323C14.2983 17.3902 17.7552 13.7907 17.9885 9.37023C18.1158 6.86522 17.1827 4.44446 15.4224 2.67626ZM8.33882 12.5066H5.98477C5.62423 12.5066 5.30611 12.212 5.30611 11.833C5.30611 11.4751 5.60302 11.1595 5.98477 11.1595H8.33882C8.69942 11.1595 9.01757 11.4541 9.01757 11.833C9.01757 12.212 8.72057 12.5066 8.33882 12.5066ZM12.0078 9.77013H5.98477C5.62423 9.77013 5.30611 9.47549 5.30611 9.09659C5.30611 8.71768 5.60302 8.42289 5.98477 8.42289H12.0078C12.3684 8.42289 12.6866 8.71768 12.6866 9.09659C12.6866 9.47549 12.3684 9.77013 12.0078 9.77013ZM12.0078 7.01257H5.98477C5.62423 7.01257 5.30611 6.71787 5.30611 6.33897C5.30611 5.98113 5.60302 5.66537 5.98477 5.66537H12.0078C12.3684 5.66537 12.6866 5.96007 12.6866 6.33897C12.6866 6.69682 12.3684 7.01257 12.0078 7.01257Z" fill="#50E138" />
                        </svg>
                    </button>
                    <span><?php echo getNumberAbbr($data['post']['total_comments']); ?> commentaires</span>
                </div>
            </div>

            <div class="activities-comments">
                <div class="comments-list">
                    <?php if (count($data['comments']) > 0) : ?>
                        <ul>
                            <?php foreach ($data['comments'] as $comment) : ?>
                                <li class="comment">
                                    <div class="sb-c-flex user">
                                        <div class="fs-c-flex">
                                            <img class="user-img" src="<?php getFileURL($comment['main_picture']); ?>" alt="Image">
                                            <div class="c-fs-col-flex">
                                                <a href="<?php concat(ROOT_URL, 'u/' . $comment['username']); ?>" class="link username">@<?php echo $comment['username']; ?></a>
                                                <span class="elapsed-time"><?php echo getElapsedTime($comment['comment_date']); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($comment['my_comment'] == 1 || $comment['my_post'] == 1) : ?>
                                            <div class="c-dropdown noselect" data-for="small-articles">
                                                <div class="c-dropdown-toggle"><i class="fas fa-ellipsis-h fa-fw"></i></div>
                                                <ul class="c-dropdown-items bl-dropdown">
                                                    <?php if ($comment['my_comment'] == 1 || $comment['my_post'] == 1) : ?>
                                                        <input type="hidden" value="<?php echo $comment['comment_id']; ?>">
                                                        <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link delete-comment"><i class="fas fa-bug fa-fw mr-r-10"></i>Supprimer</a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-content">
                                        <div class="comment-text mr-y-5"><?php echo $comment['text']; ?></div>
                                        <?php if ($comment['media_type'] == 'image') : ?>
                                            <div class="comment-media">
                                                <img class="f-width" src="<?php getFileURL($comment['media']); ?>" alt="Image">
                                            </div>
                                        <?php elseif ($comment['media_type'] == 'video') : ?>
                                            <div class="comment-media">
                                                <video class="f-width" src="<?php getFileURL($comment['media']); ?>" controls></video>
                                            </div>
                                        <?php elseif ($comment['media_type'] == 'audio') : ?>
                                            <div>
                                                <audio class="f-width" src="<?php getFileURL($comment['media']); ?>" controls></audio>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="post-comment">
                    <form id="post-comment-form" class="as-auth-form" action="<?php concat(ROOT_URL, 'data/comment'); ?>" method="post" enctype="multipart/form-data">
                        <div class="c-form-row">
                            <div>
                                <div class="sending-input">
                                    <input type="hidden" name="post_id" value="<?php echo $data['post']['post_id']; ?>">
                                    <input type="text" name="comment_text" class="input f-width sides-radius send-input" placeholder="Votre commentaire">
                                    <input type="file" name="media" hidden id="uploadCommentMedia" accept="image/png, image/jpeg, video/mp4, audio/wav, audio/mp3" data-preview="">
                                    <div class="input-buttons">
                                        <button type="button" class="upload-file" role="file-button" data-input="uploadCommentMedia">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.5938 10.9913V15.8781C16.5938 16.2658 16.2783 16.5812 15.8906 16.5812H2.10938C1.72167 16.5812 1.40625 16.2658 1.40625 15.8781V10.9913H0V15.8781C0 17.0412 0.946266 17.9874 2.10938 17.9874H15.8906C17.0537 17.9874 18 17.0412 18 15.8781V10.9913H16.5938Z" fill="#6C63FF" />
                                                <path d="M9 0.0126343L4.63065 4.38199L5.625 5.37635L8.29688 2.70448V13.6632H9.70313V2.70448L12.375 5.37635L13.3694 4.38199L9 0.0126343Z" fill="#6C63FF" />
                                            </svg>
                                        </button>
                                        <button type="button" class="record-sound">
                                            <svg width="13" height="18" viewBox="0 0 13 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.3514 8.43524C12.3514 8.14401 12.1153 7.9079 11.8241 7.9079C11.5328 7.9079 11.2968 8.14401 11.2968 8.43524C11.2968 11.259 8.99945 13.5563 6.17572 13.5563C3.35199 13.5563 1.05468 11.259 1.05468 8.43524C1.05468 8.14401 0.818538 7.9079 0.52734 7.9079C0.236073 7.9079 0 8.14401 0 8.43524C0 11.6629 2.48901 14.3198 5.64838 14.5882V16.9453H4.48123C4.18996 16.9453 3.95389 17.1814 3.95389 17.4727C3.95389 17.7639 4.18996 18 4.48123 18H7.87027C8.16147 18 8.39761 17.7639 8.39761 17.4727C8.39761 17.1814 8.16147 16.9453 7.87027 16.9453H6.70306V14.5882C9.86242 14.3197 12.3514 11.6628 12.3514 8.43524Z" fill="#6C63FF" />
                                                <path d="M6.17577 0C4.63921 0 3.3891 1.25011 3.3891 2.78668V8.43512C3.3891 9.97169 4.63921 11.2218 6.17577 11.2218C7.71241 11.2218 8.96252 9.97165 8.96252 8.43509V2.78668C8.96252 1.25011 7.71241 0 6.17577 0Z" fill="#6C63FF" />
                                            </svg>
                                        </button>
                                        <button type="submit">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.56294 13.2098V16.6883C6.56294 16.9313 6.71894 17.1466 6.94994 17.2231C7.00769 17.2418 7.06694 17.2508 7.12544 17.2508C7.30094 17.2508 7.47044 17.1683 7.57844 17.0213L9.61319 14.2523L6.56294 13.2098Z" fill="#6C63FF" />
                                                <path d="M17.7638 0.104316C17.5913 -0.0179335 17.3648 -0.0344335 17.1773 0.0638165L0.302284 8.87632C0.102783 8.98057 -0.0149666 9.19357 0.00153336 9.41782C0.0187834 9.64282 0.168033 9.83482 0.380284 9.90757L5.07154 11.5111L15.0623 2.96857L7.33129 12.2828L15.1935 14.9701C15.252 14.9896 15.3135 15.0001 15.375 15.0001C15.477 15.0001 15.5783 14.9723 15.6675 14.9183C15.81 14.8313 15.9068 14.6851 15.9315 14.5208L17.994 0.645816C18.0248 0.435816 17.9363 0.227316 17.7638 0.104316V0.104316Z" fill="#6C63FF" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uploaded-file"></div>
                    </form>
                </div>
            </div>
        </div>
    </article>
</div>