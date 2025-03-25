<x-layout>
    @vite('resources/css/dashboard.css')
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <head>
        <title>Air Quality - Admin Dashboard</title>
        @vite('resources/js/global.js')
    </head>

    <body>
        <div class="top-flex-section">
            <div class="welcome-container">
                <span class="welcome-msg">Hello, {{ Auth::user()->name }}</span>
            </div>

            <div class="container">
                <div class="toggle">
                    <div class="toggle-btn"></div>
                </div>
                <div class="text">STOP</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif
        <script>
            // Pass PHP data into JavaScript by assigning it to a global variable
            window.sensorIds = @json($sensors->pluck('id'));
        </script>


        <div class="dashboard-container">
            <div class="top-section">
                <p class="txt-1">Sri Lanka / <span class="col-blk">Colombo</span></p>
                <h1>Live Sensors</h1>
                <p class="txt-2">Air quality index (AQI) in Colombo <span id="time"></span></p>
            </div>

            <div class="grid-container" id="sensor-list">
                <table>
                    @foreach ($sensors as $sensor)
                        <tr>
                            <th>{{ $sensor->id }}</th>
                            <td>{{ $sensor->name }}</td>
                            <td>
                                @if ($sensor->status_id == 1)
                                    <span class="aqi-value" id="aqi-{{ $sensor->id }}">{{ $sensor->aqi }}</span>
                                @else
                                    <span class="aqi-value">0</span>
                                @endif
                            </td>
                            <td>
                                @if ($sensor->status_id == 1)
                                    <span class="status live">Live</span>
                                @else
                                    <span class="status na">Offline</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>

            <div class="bottom-section">
                <h1 class="S-title">Manage Sensors</h1>
                <div class="sensor-container">
                    <div class="sensor-form">
                        <h2>Add Sensors</h2>
                        <form method="POST" action="{{ route('sensor.store') }}">
                            @csrf
                            <label>Location Name</label>
                            <input type="text" name="name" required>

                            <label>Latitude</label>
                            <input type="text" name="lat" required>

                            <label>Longitude</label>
                            <input type="text" name="lng" required>

                            <label>Status</label>
                            <select name="status_id" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->status }}</option>
                                @endforeach
                            </select>


                            <button type="submit" class="delete-btn">Add Location</button>
                        </form>
                    </div>
                    <div class="sensor-delete">
                        <h2>Delete Sensors</h2>
                        <form method="POST" action="{{ route('sensor.destroy') }}">
                            @csrf
                            <label>Sensor ID</label>
                            <input type="text" name="id" required>
                            <button type="submit" class="delete-btn">Delete Sensor</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="admin-section">
                <h1 class="S-title">Manage Admins</h1>
                <div class="sensor-form">
                    <h2>Create Admin</h2>
                    <a href="{{ route('dashboard.register') }}"><button class="r-btn">Register Admin</button></a>
                </div>
            </div>
        </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".toggle");
    const text = document.querySelector(".text");

    // Check stored session state and update the toggle button
    const savedState = sessionStorage.getItem("aqiUpdating");
    if (savedState === "true") {
        toggle.classList.remove("active");
        text.textContent = "START";
    } else {
        toggle.classList.add("active");
        text.textContent = "STOP";
    }

    // Handle toggle button click
    toggle.addEventListener("click", function () {
        if (toggle.classList.contains("active")) {
            startAQIUpdates();
            text.textContent = "STOP";
            sessionStorage.setItem("aqiUpdating", "true"); // Save the state
        } else {
            stopAQIUpdates();
            text.textContent = "START";
            sessionStorage.setItem("aqiUpdating", "false"); // Save the state
        }
        toggle.classList.toggle("active");
    });
});

</script>



        <script>
            // Function to fetch updated AQI data and update the dashboard
            function fetchAQI() {
                fetch('/api/sensors/aqi') // API endpoint to get sensor AQI data
                    .then(response => response.json())
                    .then(data => {
                        // Loop through the data and update AQI values
                        data.forEach(sensor => {
                            const aqiElement = document.getElementById('aqi-' + sensor.id);
                            if (aqiElement) {
                                aqiElement.textContent = sensor.aqi; // Update the AQI value
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching AQI data:', error));
            }

            // Fetch AQI data
            setInterval(fetchAQI, 2000);

            // Fetch AQI data on page load as well
            fetchAQI();

            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(element) {
                    element.style.display = 'none';
                });
            }, 1500);
        </script>
    </body>
</x-layout>
