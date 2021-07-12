<div><span class="regular"><?php echo getNumberAbbr(count($data['posts'])); ?></span> activité(s) trouvées</div>
<?php if (count($data['posts']) > 0) : ?>
    <div class="activities-list">
        <?php foreach ($data['posts'] as $post) : ?>
            <article>
                <div class="article">
                    <div class="activity-author">
                        <div class="fs-c-flex">
                            <img class="br mr-r-5" src="<?php getFileURL($post['main_picture']); ?>" alt="Image">
                            <div class="c-fs-col-flex">
                                <a href="<?php concat(ROOT_URL, 'u/' . $post['username']); ?>" class="username link">@<?php echo $post['username']; ?></a>
                                <small class="elapsed-time"><?php echo getElapsedTime($post['posting_date']); ?></small>
                            </div>
                        </div>
                        <div class="c-dropdown noselect">
                            <div class="c-dropdown-toggle"><i class="fas fa-ellipsis-h fa-fw"></i></div>
                            <ul class="c-dropdown-items bl-dropdown">
                                <input type="hidden" value="<?php echo $post['post_id']; ?>" class="activity-id">
                                <?php if ($post['user_id'] == $_SESSION['user']) : ?>
                                    <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link save-btn"><i class="fas fa-bookmark fa-fw mr-r-10"></i>À regarder plus tard</a></li>
                                    <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link edit-btn"><i class="fas fa-highlighter fa-fw mr-r-10"></i>Modifier</a></li>
                                    <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link delete-btn"><i class="fas fa-trash fa-fw mr-r-10"></i>Supprimer</a></li>
                                <?php else : ?>
                                    <li><a href="javascript:void(0)" class="pd-y-5 pd-x-10 link save-btn"><i class="fas fa-bookmark fa-fw mr-r-10"></i>À regarder plus tard</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="activity-text"><?php echo $post['text']; ?></div>
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
                    <div class="activity-actions">
                        <div class="fs-c-flex">
                            <input type="hidden" value="<?php echo $post['post_id']; ?>">
                            <button class="like-btn<?php echo $post['liked'] == 1 ? ' liked' : ''; ?>">
                                <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.9573 10.5387C14.7282 7.85426 13.3393 6.17199 12.1143 4.68747C10.9798 3.31316 10.0001 2.12635 10.0001 0.375644C10.0001 0.235008 9.91245 0.106489 9.7737 0.0420487C9.6345 -0.0227789 9.4674 -0.012173 9.3408 0.0705855C7.50075 1.25557 5.96559 3.25275 5.42929 5.15837C5.05699 6.48505 5.00774 7.97652 5.00082 8.9615C3.30161 8.63487 2.91668 6.34738 2.91261 6.32246C2.89347 6.20381 2.81294 6.10056 2.69574 6.04416C2.57735 5.98851 2.43816 5.98447 2.31813 6.03793C2.22903 6.07676 0.13101 7.03617 0.00893998 10.8669C0.000389981 10.9943 0 11.1221 0 11.2499C0 14.9714 3.36465 17.9994 7.5 17.9994C7.5057 17.9997 7.51185 18.0005 7.51665 17.9994C7.5183 17.9994 7.51995 17.9994 7.5219 17.9994C11.6472 17.9888 15 14.9648 15 11.2499C15 11.0628 14.9573 10.5387 14.9573 10.5387ZM7.5 17.2495C6.12135 17.2495 5 16.1744 5 14.8528C5 14.8078 4.99961 14.7623 5.00324 14.7067C5.01992 14.1494 5.13754 13.7689 5.26653 13.5158C5.50824 13.9831 5.94034 14.4126 6.6423 14.4126C6.87255 14.4126 7.059 14.2449 7.059 14.0376C7.059 13.5037 7.07115 12.8879 7.2189 12.3319C7.3503 11.839 7.6644 11.3147 8.06235 10.8943C8.23935 11.44 8.5845 11.8815 8.9214 12.3125C9.4035 12.9293 9.90195 13.5667 9.98955 14.6539C9.9948 14.7185 10.0001 14.7833 10.0001 14.8528C10.0001 16.1744 8.87865 17.2495 7.5 17.2495Z" fill="#888888" />
                                </svg>
                            </button>
                            <span class="reactions-value"><?php echo $post['liked'] == 1 ? 'Vous et ' . getNumberAbbr($post['total_reactions'] - 1) : getNumberAbbr($post['total_reactions']); ?> personnes</span>
                            <span class="gray-bubble"></span>
                            <button>
                                <svg width="16" height="16" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.4224 2.67626C13.662 0.908068 11.2443 -0.0602355 8.74187 0.00290562C3.75793 0.150255 -0.144356 4.25503 0.004099 9.20184C0.025309 10.2333 0.237394 11.2436 0.61913 12.1908C0.93725 12.9908 1.36142 13.7276 1.89161 14.4011L1.14934 16.1483C0.852425 16.822 1.17055 17.6008 1.84921 17.8954C2.08249 18.0007 2.3582 18.0218 2.61269 17.9797L6.0484 17.3902C7.27846 17.8113 8.57222 17.9585 9.86582 17.8323C14.2983 17.3902 17.7552 13.7907 17.9885 9.37023C18.1158 6.86522 17.1827 4.44446 15.4224 2.67626ZM8.33882 12.5066H5.98477C5.62423 12.5066 5.30611 12.212 5.30611 11.833C5.30611 11.4751 5.60302 11.1595 5.98477 11.1595H8.33882C8.69942 11.1595 9.01757 11.4541 9.01757 11.833C9.01757 12.212 8.72057 12.5066 8.33882 12.5066ZM12.0078 9.77013H5.98477C5.62423 9.77013 5.30611 9.47549 5.30611 9.09659C5.30611 8.71768 5.60302 8.42289 5.98477 8.42289H12.0078C12.3684 8.42289 12.6866 8.71768 12.6866 9.09659C12.6866 9.47549 12.3684 9.77013 12.0078 9.77013ZM12.0078 7.01257H5.98477C5.62423 7.01257 5.30611 6.71787 5.30611 6.33897C5.30611 5.98113 5.60302 5.66537 5.98477 5.66537H12.0078C12.3684 5.66537 12.6866 5.96007 12.6866 6.33897C12.6866 6.69682 12.3684 7.01257 12.0078 7.01257Z" fill="#50E138" />
                                </svg>
                            </button>
                            <span><?php echo getNumberAbbr($post['total_comments']); ?> commentaires</span>
                        </div>
                        <a href="<?php concat(ROOT_URL, 'activities/' . $post['post_id']); ?>">
                            <div class="fs-c-flex">
                                <button>
                                    <svg width="20" height="13" viewBox="0 0 20 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 3.21875C8.22421 3.21875 6.78137 4.66159 6.78137 6.4374C6.78137 8.2132 8.22421 9.65605 10 9.65605C11.7758 9.65605 13.2187 8.2132 13.2187 6.4374C13.2187 4.66159 11.7758 3.21875 10 3.21875ZM9.75585 5.4829C9.35629 5.4829 9.02333 5.81587 9.02333 6.21542H7.95784C7.98004 5.21653 8.77915 4.41742 9.75585 4.41742V5.4829Z" fill="#6C63FF" />
                                        <path d="M19.7669 5.77136C18.6792 4.41731 14.7947 0 10 0C5.20533 0 1.32075 4.41731 0.233074 5.77136C-0.0776914 6.14872 -0.0776914 6.70366 0.233074 7.10322C1.32075 8.45727 5.20533 12.8746 10 12.8746C14.7947 12.8746 18.6792 8.45727 19.7669 7.10322C20.0777 6.72586 20.0777 6.17092 19.7669 5.77136ZM10 11.0988C7.42508 11.0988 5.33851 9.01221 5.33851 6.43729C5.33851 3.86238 7.42508 1.7758 10 1.7758C12.5749 1.7758 14.6615 3.86238 14.6615 6.43729C14.6615 9.01221 12.5749 11.0988 10 11.0988Z" fill="#6C63FF" />
                                    </svg>
                                </button>
                                <span><?php echo getNumberAbbr($post['total_views']); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>