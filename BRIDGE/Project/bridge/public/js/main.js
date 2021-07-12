let mic, recorder, soundFile, state = 0;

function setup() {
    createCanvas(0, 0);

    // Initialize recording tools
    mic = new p5.AudioIn();
    recorder = new p5.SoundRecorder();
    recorder.setInput(mic);
    soundFile = new p5.SoundFile();
}

document.querySelector('body').onload = function () {
    $('#loading-modal').remove();
    fixeSticky();
};

$(document).ready(function () {
    const hostname = window.location.hostname;

    /* -------- CHARTS -------- */
    const membersJoinedCanvas = document.querySelector('#membersJoinedLastTenDays');

    if (membersJoinedCanvas != null) {
        $('#membersJoinedLastTenDays').attr('height', '100vh');
        const membersJoinedChartContext = membersJoinedCanvas.getContext('2d');
        const gradientFill = membersJoinedChartContext.createLinearGradient(0, 0, 0, 500);
        gradientFill.addColorStop(0, "rgba(108, 99, 255, 0.4)");
        gradientFill.addColorStop(1, "rgba(244, 144, 128, 0.1)");

        let month = [];
        let monthlyData = [];

        $.get("http://" + hostname + "/bridge/data/getMonthlyData", {
                count: 10
            },
            function (data, textStatus, jqXHR) {
                const result = JSON.parse(data);

                for (let i = 0; i < result.length; i++) {
                    month.push(result[i].date);
                    monthlyData.push(result[i].members);
                }

                // Global Options
                Chart.defaults.global.defaultFontFamily = 'inherit';
                Chart.defaults.global.defaultFontSize = 18;
                Chart.defaults.global.defaultFontColor = '#666';

                let massPopChart = new Chart(membersJoinedChartContext, {
                    type: 'line',
                    data: {
                        labels: month,
                        datasets: [{
                            label: 'Membres',
                            data: monthlyData,
                            backgroundColor: [
                                'rgba(108, 99, 255, 0.2)'
                            ],
                            pointBorderColor: "#5b54db",
                            pointBackgroundColor: "#5b54db",
                            pointBorderWidth: 2,
                            borderWidth: 1,
                            borderColor: '#6c63ff',
                            backgroundColor: gradientFill
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        }
                    }
                });
            }
        );
    }

    // Seven days


    /* -------- FIXING -------- */
    // Fixe to show scroll only whene height > max-height
    fixeMaxScrollHeight();

    // Fixe the margin top of main page content
    fixeMainMarginTop();

    // Side radius 50%
    setSidesRadius();

    // Video Plyr
    plyr.setup('video:not([class~="no-media-effect"]), audio:not([class~="no-media-effect"])');

    /* -------- EVENTS -------- */
    // Toggle drop down
    $('.c-dropdown-toggle').on('click', function () {
        $(this).parent().toggleClass('active');
    });

    // Toggle panel
    $('.c-panel-toggle').on('click', function () {
        $(this).next().slideToggle('fast').parent().toggleClass('active');
    });

    // Toggle tab items
    $('.c-tab-item').on('click', function () {
        if (!$(this).hasClass('active')) {
            let oldIndex = $('.c-tab-item.active').attr('data-index'),
                newIndex = $(this).attr('data-index');

            $(this).addClass('active').siblings('.c-tab-item.active').removeClass('active');
            $('.c-tab-container').eq(oldIndex).removeClass('active');
            $('.c-tab-container').eq(newIndex).addClass('active');

            $('.c-tab-line').width($('.c-tab-item.active').innerWidth() + 'px').css({
                'left': dimNodeListSum($('.c-tab-item.active').prevAll(), 'w', true) + 'px'
            });

            fixeMaxScrollHeight();
            fixeSticky();
        }
    });

    // Drop down value select
    $('.c-dropdown-items li').on('click', function () {
        if (!$(this).hasClass('c-dropdown-no-effect'))
            $(this).parents('.c-dropdown').removeClass('active').find('input').val($(this).attr('data-value'));
    });

    // Toggle password
    $('.togglePassword').on('click', function () {
        let eyes = [
            'M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z',
            'M11.83,9L15,12.16C15,12.11 15,12.05 15,12A3,3 0 0,0 12,9C11.94,9 11.89,9 11.83,9M7.53,9.8L9.08,11.35C9.03,11.56 9,11.77 9,12A3,3 0 0,0 12,15C12.22,15 12.44,14.97 12.65,14.92L14.2,16.47C13.53,16.8 12.79,17 12,17A5,5 0 0,1 7,12C7,11.21 7.2,10.47 7.53,9.8M2,4.27L4.28,6.55L4.73,7C3.08,8.3 1.78,10 1,12C2.73,16.39 7,19.5 12,19.5C13.55,19.5 15.03,19.2 16.38,18.66L16.81,19.08L19.73,22L21,20.73L3.27,3M12,7A5,5 0 0,1 17,12C17,12.64 16.87,13.26 16.64,13.82L19.57,16.75C21.07,15.5 22.27,13.86 23,12C21.27,7.61 17,4.5 12,4.5C10.6,4.5 9.26,4.75 8,5.2L10.17,7.35C10.74,7.13 11.35,7 12,7Z'
        ];

        $(this).prev().attr('type', $(this).prev().attr('type') === 'text' ? 'password' : 'text').next().find('path').attr('d', $(this).prev().attr('type') === 'text' ? eyes[1] : eyes[0]);
    });

    // Close modal target
    $('.c-modal').on('click', function (e) {
        if (e.target === $(this)[0])
            $(this).removeClass('active');
    });

    // Opan file dialog with button
    $('button[role="file-button"]').on('click', function () {
        $('#' + $(this).attr('data-input')).click();
    });

    // On input of a hidden input
    $('input[hidden]:not([data-preview])').on('input', function () {
        $('#' + $(this).attr('data-to')).text($(this).val().split('\\')[$(this).val().split('\\').length - 1]);
    });

    $('input[hidden][data-preview]').on('change', function () {
        let file = $(this)[0].files[0],
            html, reader, uploadedFileContainer = $(this).parents('.post-comment').find('.uploaded-file');

        const loading = '<div class="file-loading"><div class="circle"></div></div>';

        uploadedFileContainer.css('display', 'none');
        // Clear the container
        uploadedFileContainer.empty();

        if (file && isInArray(['image/png', 'image/jpeg', 'video/mp4', 'audio/wav', 'audio/mpeg'], file.type)) {
            // Loading
            uploadedFileContainer.empty().append(loading);
            uploadedFileContainer.css('display', 'block');

            reader = new FileReader();
            // Preview it
            reader.addEventListener('load', function () {
                switch (true) {
                    case isInArray(['image/png', 'image/jpeg'], file.type):
                        html = '<img src="' + this.result + '" alt="Image">';
                        break;

                    case isInArray(['video/wav', 'video/mp4'], file.type):
                        html = '<video src="' + this.result + '" controls></video>';
                        break;

                    default:
                        html = '<audio src="' + this.result + '" controls type="' + file.type + '"></audio>';
                        break;
                }

                uploadedFileContainer.empty().append(html);
                plyr.setup(uploadedFileContainer.children()[0]);

                console.log($('#uploadCommentMedia').val());
            });

            reader.readAsDataURL(file);
        } else
            alert('La format du fichier non supportée!');
    });

    // Profile images change
    $('.profile-images button').on('click', function () {
        $(this).prev('input').click();
    });

    $('.profile-images input[type="file"]').on('change', function () {
        let file = $(this)[0].files[0],
            reader, clientImg = $(this).prev('img');

        if (file && isInArray(['image/png', 'image/jpeg'], file.type)) {
            reader = new FileReader();
            // Preview it
            reader.addEventListener('load', function () {
                clientImg.attr('src', this.result);
            });

            reader.readAsDataURL(file);
        } else
            alert('La format du fichier non supportée!');
    });

    $(window).on('scroll', function () {
        if ($(this).scrollTop() < 200)
            $('#scrollToBody').removeClass('active');
        else
            $('#scrollToBody').addClass('active');
    });

    // Record sound
    $('.record-sound').on('click', function () {
        getAudioContext().resume();

        if (state == 0) {
            state = 1;
            $(this).addClass('recording');
            mic.start();
            recorder.record(soundFile);
        } else if (state == 1) {
            $(this).removeClass('recording');
            $(this).addClass('stoped');
            state = 2;
            recorder.stop();
        } else if (state == 2) {
            $(this).removeClass('stoped');
            state = 0;
            mic.stop();
            saveSound(soundFile, getRandomString(20) + '.wav');
        }
    });

    // Close flash
    $('.close-flash').on('click', function () {
        $(this).parent().animate({
            'opacity': 0
        }, 'fast', function () {
            $(this).remove();
        });
    });

    // Add text to database
    $('#addTextModal form').on('submit', function () {
        $(this).find('.flash').remove();
        if ($(this).find('#textToAdd').val() == '') {
            $(this).prepend(getFlash('flash-error', 'L\'écriture du texte dans la zone de texte est requise ci-dessous!'));
            return false;
        }
    });

    // Add media to database
    $('#addMediaModal form').on('submit', function () {
        $(this).find('.flash').remove();
        if ($(this).find('#mediaInput').val() == '') {
            $(this).prepend(getFlash('flash-error', 'Le fichier à télécharger est obligatoire!'));
            return false;
        }

        $.ajax({
            url: "http://" + hostname + "/bridge/home/media",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                let state = parseInt(data);

                if (state == -1)
                    $('#addMediaModal form').prepend(getFlash('flash-error', 'Seuls les fichiers dont l\'extension est (.png, .jpg, .jpeg, .mp3, .mp4 et .wav) sont autorisés!'));
                else if (state == -2)
                    $('#addMediaModal form').prepend(getFlash('flash-error', 'Le fichier ne doit pas dépasser 4 Mo!'));
                else if (state == 0)
                    $('#addMediaModal form').prepend(getFlash('flash-danger', 'Il y a une erreur sur le serveur, veuillez actualiser la page et réessayer!'));
                else
                    window.location.replace("http://" + hostname + "/bridge/home");
            }
        });

        return false;
    });
});

$(document).on("mousedown", function (event) {
    let trigger = $('.c-dropdown[class~="active"]');

    if (!trigger.has(event.target).length)
        trigger.removeClass('active');
});

/* FUNCTIONS */
// Get flash html code
function getFlash(type, message) {
    let flash = '<div class="flash ' + type + '">';
    flash += '<span class="regular">' + message + '</span>';
    flash += '<svg class="close-flash" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="26" height="26" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>';
    flash += '</div>';

    return flash;
}