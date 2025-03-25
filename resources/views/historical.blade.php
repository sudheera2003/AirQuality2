<x-layout>

    <head>
        @vite('resources/css/home.css')
        @vite('resources/css/historical.css')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Historical AQI Data</title>
    </head>

    <body>
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
