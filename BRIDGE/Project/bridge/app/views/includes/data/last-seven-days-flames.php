<div>
    <canvas id="flamesLastSevenDays" class="section-container"></canvas>
    <script>
        const flamesLastSevenDaysCanvas = document.getElementById('flamesLastSevenDays');

        if (flamesLastSevenDaysCanvas != null) {
            $('#flamesLastSevenDays').attr('width', '100%');
            const flamesLastSevenDaysContext = flamesLastSevenDaysCanvas.getContext('2d');
            const gradientFill = flamesLastSevenDaysContext.createLinearGradient(0, 0, 0, 100);
            gradientFill.addColorStop(0, "rgba(108, 99, 255, 0.4)");
            gradientFill.addColorStop(1, "rgba(244, 144, 128, 0.1)");

            let days = [];
            let sevenDaysData = [];

            $.get("http://" + window.location.hostname + "/bridge/data/sevenDaysData", {
                    count: 7
                },
                function(data, textStatus, jqXHR) {
                    const result = JSON.parse(data);

                    for (let i = 0; i < result.length; i++) {
                        days.push(i == result.length ? 0 : -6 + i);
                        sevenDaysData.push(result[i].members);
                    }

                    // Global Options
                    Chart.defaults.global.defaultFontFamily = 'inherit';
                    Chart.defaults.global.defaultFontSize = 18;
                    Chart.defaults.global.defaultFontColor = '#666';

                    let massPopChart = new Chart(flamesLastSevenDaysContext, {
                        type: 'line',
                        data: {
                            labels: days,
                            datasets: [{
                                label: 'Flammes',
                                data: sevenDaysData,
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
    </script>
</div>