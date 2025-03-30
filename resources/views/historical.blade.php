<x-layout>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite('resources/css/home.css')
        @vite('resources/css/historical.css')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>Historical AQI Data</title>
        <style>
            .chart-container {
                position: relative;
                margin: 20px 0;
                background: white;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                min-height: 400px;
            }

            .chart-controls {
                display: flex;
                gap: 15px;
                margin-bottom: 20px;
                flex-wrap: wrap;
            }

            .chart-controls select {
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                background: white;
                min-width: 150px;
                font-size: 14px;
            }

            #aqiChart {
                width: 100% !important;
                height: 400px !important;
            }

            .error-message {
                padding: 10px;
                background: #ffeeee;
                border: 1px solid #ffcccc;
                border-radius: 4px;
                margin-bottom: 15px;
            }

            /* Add smooth transitions */
            #aqiChart {
                transition: opacity 0.3s ease;
            }
        </style>
    </head>

    <body>

        {{-- -------------------------------------------------------------------------------- --}}

        <div class="chart-container">
            <div class="chart-controls">
                <select id="timePeriod" class="form-control">
                    <option value="day">Last 24 Hours</option>
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                </select>
                <select id="sensorSelect" class="form-control">
                    @foreach ($sensors as $sensor)
                        <option value="{{ $sensor->id }}">{{ $sensor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="chartError" class="error-message" style="display: none; color: red; margin: 10px 0;"></div>
            <canvas id="aqiChart" height="400"></canvas>
        </div>


        {{-- ------------------------------------------------------------------------------------ --}}
        <div class="container">
            <h1>Historical AQI Data</h1>
            <h2>Click to expand</h2>
            <div class="sensor-container">
                <div class="sensor-list">
                    @foreach ($sensors as $sensor)
                        <div class="sensor">
                            <button class="sensor-btn" data-sensor="{{ $sensor->id }}"
                                data-sensor-name="{{ $sensor->name }}">
                                {{ $sensor->name }}
                            </button>
                            <div class="months" style="display: none;">
                                @for ($m = 1; $m <= 12; $m++)
                                    <button class="month-btn" data-sensor="{{ $sensor->id }}"
                                        data-month="{{ $m }}">
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </button>
                                    <div class="days" style="display: none;"></div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="aqi-table" style="display: none;">
                    <h2 class="date-title">AQI Data for <span id="selected-date"></span></h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>AQI</th>
                            </tr>
                        </thead>
                        <tbody id="aqi-data">
                            <!-- Data will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('aqiChart');
                const ctx = canvas.getContext('2d');
                const timePeriodSelect = document.getElementById('timePeriod');
                const sensorSelect = document.getElementById('sensorSelect');

                let aqiChart = null;

                function initChart() {
                    aqiChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'AQI',
                                data: [],
                                borderWidth: 2,
                                borderColor: '#4CAF50',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0, // This makes the line straight between points
                                fill: false, // No fill under the line
                                pointRadius: 4, // Size of data points
                                pointHoverRadius: 6, // Size when hovering
                                pointBackgroundColor: '#4CAF50',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 1,
                                showLine: true // Ensures line is shown
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false, // Don't force zero baseline
                                    suggestedMin: 0, // But suggest starting at 0
                                    suggestedMax: 300, // Suggest max at 300 unless data exceeds
                                    title: {
                                        display: true,
                                        text: 'AQI Value',
                                        font: {
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            // Add AQI level indicators
                                            if (value === 50) return '50 (Good)';
                                            if (value === 100) return '100 (Moderate)';
                                            if (value === 150) return '150 (Unhealthy)';
                                            if (value === 200) return '200 (Very Unhealthy)';
                                            if (value === 300) return '300 (Hazardous)';
                                            return value;
                                        }
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Time',
                                        font: {
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            return `AQI: ${context.parsed.y}`;
                                        },
                                        title: function(context) {
                                            return context[0].label;
                                        }
                                    }
                                },
                                legend: {
                                    display: false
                                },
                                annotation: {
                                    annotations: {
                                        line1: {
                                            type: 'line',
                                            yMin: 50,
                                            yMax: 50,
                                            borderColor: 'rgb(76, 175, 80)',
                                            borderWidth: 1,
                                            borderDash: [6, 6],
                                            label: {
                                                content: 'Good',
                                                enabled: true,
                                                position: 'left'
                                            }
                                        },
                                        line2: {
                                            type: 'line',
                                            yMin: 100,
                                            yMax: 100,
                                            borderColor: 'rgb(255, 235, 59)',
                                            borderWidth: 1,
                                            borderDash: [6, 6],
                                            label: {
                                                content: 'Moderate',
                                                enabled: true,
                                                position: 'left'
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                function loadChartData() {
                    const sensorId = sensorSelect.value;
                    const period = timePeriodSelect.value;

                    // Show loading state
                    canvas.style.opacity = '0.5';
                    document.getElementById('chartError').style.display = 'none';

                    fetch(`/api/sensors/${sensorId}/history?period=${period}`)
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.error || `HTTP error! status: ${response.status}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data || data.error) {
                                throw new Error(data?.error || 'No data available');
                            }

                            let labels = [];
                            let values = [];

                            if (period === 'day') {
                                // Create labels for all 24 hours, even if no data
                                labels = Array.from({
                                    length: 24
                                }, (_, i) => `${i}:00`);
                                values = Array(24).fill(null);

                                // Fill in actual data points
                                data.forEach(item => {
                                    values[item.hour] = item.avg_aqi;
                                });
                            } else if (period === 'week') {
                                // Get last 7 days
                                const days = [];
                                for (let i = 6; i >= 0; i--) {
                                    const date = new Date();
                                    date.setDate(date.getDate() - i);
                                    days.push(date.toLocaleDateString('en-US', {
                                        weekday: 'short',
                                        month: 'short',
                                        day: 'numeric'
                                    }));
                                }

                                labels = days;
                                values = Array(7).fill(null);

                                // Fill in actual data points
                                data.forEach(item => {
                                    const date = new Date(item.date);
                                    const today = new Date();
                                    const diffTime = Math.abs(today - date);
                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                                    if (diffDays <= 6) {
                                        values[6 - diffDays] = item.avg_aqi;
                                    }
                                });
                            } else {
                                // Month view - last 4 weeks
                                labels = Array.from({
                                    length: 4
                                }, (_, i) => `Week ${new Date().getWeek() - (3 - i)}`);
                                values = Array(4).fill(null);

                                data.forEach(item => {
                                    const currentWeek = new Date().getWeek();
                                    const weekDiff = currentWeek - item.week;

                                    if (weekDiff <= 3) {
                                        values[3 - weekDiff] = item.avg_aqi;
                                    }
                                });
                            }

                            // Update chart
                            aqiChart.data.labels = labels;
                            aqiChart.data.datasets[0].data = values;
                            aqiChart.options.scales.x.title.text =
                                period === 'day' ? 'Hour of Day' :
                                period === 'week' ? 'Date' : 'Week of Month';
                            aqiChart.update();
                        })
                        .catch(error => {
                            console.error('Error loading chart data:', error);
                            const errorElement = document.getElementById('chartError');
                            errorElement.textContent = `Error: ${error.message}`;
                            errorElement.style.display = 'block';
                        })
                        .finally(() => {
                            canvas.style.opacity = '1';
                        });
                }

                // Add getWeek method to Date prototype for month view
                Date.prototype.getWeek = function() {
                    const date = new Date(this.getTime());
                    date.setHours(0, 0, 0, 0);
                    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
                    const week1 = new Date(date.getFullYear(), 0, 4);
                    return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() +
                        6) % 7) / 7);
                };

                timePeriodSelect.addEventListener('change', loadChartData);
                sensorSelect.addEventListener('change', loadChartData);

                initChart();
                loadChartData();
            });


            //-------------------------------------------------------------------------------------------------------------------------------

            $(document).ready(function() {
                let lastClickedDay = null; // Store the last clicked day to toggle it

                // Toggle months visibility on sensor button click
                $(".sensor-btn").click(function() {
                    let monthsContainer = $(this).next(".months");
                    monthsContainer.toggle();
                    // Hide AQI data if the months are collapsed
                    $(".aqi-table").hide();
                });

                $(".month-btn").click(function() {
                    let sensorId = $(this).data("sensor");
                    let month = $(this).data("month");
                    let daysContainer = $(this).next(".days");

                    // Hide all other days before toggling the selected one
                    $(this).closest(".months").find(".days").not(daysContainer).hide();

                    // If the selected days are already visible, collapse them
                    if (daysContainer.is(":visible")) {
                        daysContainer.hide();
                        $(".aqi-table").hide(); // Hide AQI data when days are collapsed
                    } else {
                        // Fetch days for the selected month
                        $.get(`/historical/days/${sensorId}/${month}`, function(data) {
                            let daysHtml = "";

                            if (data.days.length === 0) {
                                // If there are no available days, display a message
                                daysHtml =
                                    `<p class="error-message">There is no data for this month</p>`;
                            } else {
                                // Otherwise, display the available days
                                data.days.forEach(day => {
                                    daysHtml +=
                                        `<button class="day-btn" data-sensor="${sensorId}" data-month="${month}" data-day="${day}">${day}</button>`;
                                });
                            }

                            // Update the days container, show it, and hide AQI table
                            daysContainer.html(daysHtml).show();
                            $(".aqi-table").hide();
                        });
                    }
                });



                // When a specific day is clicked, display the AQI data
                $(document).on("click", ".day-btn", function() {
                    let sensorId = $(this).data("sensor");
                    let month = $(this).data("month");
                    let day = $(this).data("day");

                    // Check if this day was already clicked
                    if (lastClickedDay === `${sensorId}-${month}-${day}`) {
                        // If the same day is clicked, hide the AQI data and collapse it
                        $(".aqi-table").hide();
                        $(this).removeClass("active"); // Optionally remove the active class
                        lastClickedDay = null; // Reset the last clicked day
                    } else {
                        // Show the AQI table and fetch data for the new day
                        // Get the sensor name using the data-sensor-name attribute
                        let sensorName = $(`button[data-sensor="${sensorId}"]`).data("sensor-name");

                        // Update the selected date display
                        $("#selected-date").text(`${sensorName} - ${month}/${day}`);
                        $(".aqi-table").show();

                        // Fetch the AQI data for the selected sensor, month, and day
                        $.get(`/historical/data/${sensorId}/${month}/${day}`, function(data) {
                            let tableHtml = "";
                            data.aqi.forEach(record => {
                                tableHtml +=
                                    `<tr><td>${record.time}</td><td>${record.aqi}</td></tr>`;
                            });
                            $("#aqi-data").html(tableHtml);
                        });

                        // Mark this day as the last clicked day
                        lastClickedDay = `${sensorId}-${month}-${day}`;
                        // Optionally add an "active" class to indicate the selected day
                        $(".day-btn").removeClass("active");
                        $(this).addClass("active");
                    }
                });
            });
        </script>
    </body>
</x-layout>
