<div>
    <canvas id="worldFriendsResume" class="section-container"></canvas>
    <script>
        const worldFriendsResumeCanvas = document.getElementById('worldFriendsResume');

        if (worldFriendsResumeCanvas != null) {
            $('#worldFriendsResume').attr('width', '100%');
            const worldFriendsResumeContext = worldFriendsResumeCanvas.getContext('2d');

            let countries = [];
            let members = [];

            $.get("http://" + window.location.hostname + "/bridge/data/friendsAroundWorld",
                function(data, textStatus, jqXHR) {
                    const result = JSON.parse(data);

                    if (result.length == 0) {
                        // Remove the canvas and set a text
                        let canvasParent = $('#worldFriendsResume').parent();
                        $('#worldFriendsResume').remove();

                        let searchForm = '<div class="as-nav-search-input mr-t-15">';
                        searchForm += '<form action="http://' + window.location.hostname + '/bridge/search" method="get">';
                        searchForm += '<input type="hidden" name="tab" value="bridgers">';
                        searchForm += '<input class="input f-width" type="search" name="q" placeholder="Trouver un ami sur Bridge" id="searchInput">';
                        searchForm += '<button type="submit">';
                        searchForm += '<svg width="18" height="18" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27.8291 26.1794L19.6953 18.0456C21.238 16.141 22.1667 13.7196 22.1667 11.0834C22.1667 4.97203 17.1947 0 11.0833 0C4.97198 0 0 4.97203 0 11.0834C0 17.1947 4.97203 22.1667 11.0834 22.1667C13.7196 22.1667 16.141 21.238 18.0456 19.6954L26.1794 27.8292C26.4073 28.0569 26.7765 28.0569 27.0044 27.8292L27.8292 27.0044C28.0569 26.7765 28.0569 26.4072 27.8291 26.1794ZM11.0834 19.8334C6.25834 19.8334 2.33336 15.9084 2.33336 11.0834C2.33336 6.25834 6.25834 2.33336 11.0834 2.33336C15.9084 2.33336 19.8334 6.25834 19.8334 11.0834C19.8334 15.9084 15.9084 19.8334 11.0834 19.8334Z" fill="#333333" /></svg>';
                        searchForm += '</button></form></div>';

                        canvasParent.append('<div class="message center mr-t-5">Vous n\'avez aucun ami sur Facebook</div>').append(searchForm);
                    }

                    for (let i = 0; i < result.length; i++) {
                        countries.push(result[i]['country']);
                        members.push(result[i]['members_count']);
                    }

                    // Global Options
                    Chart.defaults.global.defaultFontFamily = 'inherit';
                    Chart.defaults.global.defaultFontSize = 18;
                    Chart.defaults.global.defaultFontColor = '#666';

                    let massPopChart = new Chart(worldFriendsResumeContext, {
                        type: 'pie',
                        data: {
                            labels: countries,
                            datasets: [{
                                label: 'Vos amis dans le monde',
                                data: members,
                                backgroundColor: 'rgb(142, 136, 255)'
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
    </script>
</div>