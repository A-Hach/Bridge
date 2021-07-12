$(function () {
    const hostname = window.location.hostname,
        sendFriendship = "<i class='fas fa-plus fa-fw'></i>Demande d'amitié",
        confirmFriendship = "<i class='fas fa-hands-helping fa-fw'></i>Confirmer l'amitié",
        cancelFriendship = "<i class='fas fa-user-minus fa-fw'></i>Annuler l'amitié",
        destroyFriendship = "<i class='fas fa-user-times fa-fw'></i>Détruisez l'amitié",
        likeSound = document.querySelector('#likeSound');

    // Like activity
    $('.like-btn').on('click', function () {
        let $input = $(this).prev();

        likeSound.play();

        if ($(this).hasClass('liked')) {
            // Unlike
            $(this).removeClass('liked');
            $(this).siblings('.reactions-value').html($(this).siblings('.reactions-value').html().replace('Vous et ', ''));
        } else {
            // Like
            $(this).addClass('liked');
            $(this).siblings('.reactions-value').html('Vous et ' + $(this).siblings('.reactions-value').html());
        }

        $.post('http://' + hostname + '/bridge/posts/react', {
                post: $input.val()
            },
            function (data, textStatus, jqXHR) {}
        );
    });

    // Delete comment
    $('.delete-comment').on('click', function () {
        let $commentInput = $(this).parent().prev(),
            $comment = $(this).parents('.comment');

        $.post('http://' + hostname + '/bridge/data/deleteComment', {
                comment: $commentInput.val()
            },
            function (data, textStatus, jqXHR) {
                $comment.remove();
            }
        );
    });

    // Edit activity
    $('.edit-btn').on('click', function () {
        let $activityText = $(this).parents('.article').find('.activity-text'),
            $text = $activityText.text();


        let form = '<form action="http://' + hostname + '/bridge/posts/edit" method="post">';
        form += '<input type="hidden" name="post_id" value="' + $(this).parents('.c-dropdown-items').find('.activity-id').val() + '">';
        form += '<textarea name="post_text" class="input f-width" placeholder="Ecrivez ici">' + $text + '</textarea>';
        form += '<input type="submit" value="Enregistrer" name="save_activity" class="c-sm-btn c-btn-success mr-t-10">';
        form += '</form>';

        $activityText.empty().append(form);
    });

    // Save activity
    $('.save-btn').on('click', function () {
        let $activityID = $(this).parent().prevAll('.activity-id').val();

        $.post('http://' + hostname + '/bridge/posts/save', {
                post: $activityID
            },
            function (data, textStatus, jqXHR) {}
        );
    });

    // Delete activity
    $('.delete-btn').on('click', function () {
        let $activityID = $(this).parent().prevAll('.activity-id').val(),
            $article = $(this).parents('article');

        $.post('http://' + hostname + '/bridge/posts/delete', {
                post: $activityID
            },
            function (data, textStatus, jqXHR) {
                console.log(data);
                $article.remove();
            }
        );
    });

    // Unsave activity
    $('.unsave-btn').on('click', function () {
        let $activityID = $(this).parent().prevAll('.activity-id').val(),
            $article = $(this).parents('article');

        $.post('http://' + hostname + '/bridge/posts/unsave', {
                post: $activityID
            },
            function (data, textStatus, jqXHR) {
                $article.remove();
            }
        );
    });

    // To send friendship request
    $('.send-friendship-request').on('click', function () {
        let $userInput = $(this).prev();

        // Change button
        $(this).removeClass('send-friendship-request')
            .addClass('cancel-friendship-request')
            .empty()
            .html(cancelFriendship);

        sendFriendshipRequest($userInput.val());
    });

    // To confirm friendship request
    $('.confirm-friendship-request').on('click', function () {
        let $userInput = $(this).prev();

        // Change button
        $(this).removeClass('confirm-friendship-request')
            .removeClass('c-btn-accept')
            .addClass('destroy-friendship')
            .addClass('c-btn-refuse')
            .empty()
            .html(destroyFriendship);

        confirmFriendshipRequest($userInput.val());
    });

    $('.confirm-friendship-request-inv').on('click', function () {
        let $userInput = $(this).prevAll('input');
        console.log($userInput);

        // Remove buttons
        $(this).remove();
        $('.cancel-friendship-request-inv').remove();

        confirmFriendshipRequest($userInput.val());
    });

    $('.cancel-friendship-request-inv').on('click', function () {
        let $userInput = $(this).prevAll('input');
        console.log($userInput);

        // Change button
        $(this).remove();
        $('.confirm-friendship-request-inv').remove();

        cancelFriendshipRequest($userInput.val());
    });

    // To cancel friendship request
    $('.cancel-friendship-request').on('click', function () {
        let $userInput = $(this).prev();

        // Change button
        $(this).removeClass('cancel-friendship-request')
            .addClass('send-friendship-request')
            .empty()
            .html(sendFriendship);

        cancelFriendshipRequest($userInput.val());

        // Change click function
        $('.send-friendship-request').on('click', sendFriendshipRequest);
    });

    // To destroy friendship
    $('.destroy-friendship').on('click', function () {
        let $userInput = $(this).prev();

        // Change button
        $(this).removeClass('destroy-friendship')
            .addClass('send-friendship-request')
            .empty()
            .html(sendFriendship);

        destroyFriendshipRequest($userInput.val());
    });

    // FUNCTIONS
    function sendFriendshipRequest(userID) {
        $.post('http://' + hostname + '/bridge/data/sendFriendship', {
                user: userID
            },
            function (data, textStatus, jqXHR) {}
        );
    }

    function cancelFriendshipRequest(userID) {
        $.post('http://' + hostname + '/bridge/data/cancelFriendship', {
                user: userID
            },
            function (data, textStatus, jqXHR) {}
        );
    }

    function destroyFriendshipRequest(userID) {
        $.post('http://' + hostname + '/bridge/data/destroyFriendship', {
                user: userID
            },
            function (data, textStatus, jqXHR) {}
        );
    }

    function confirmFriendshipRequest(userID) {
        $.post('http://' + hostname + '/bridge/data/confirmFriendship', {
                user: userID
            },
            function (data, textStatus, jqXHR) {}
        );
    }
});