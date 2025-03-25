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
                    <div class="toggle-btn" onclick="Animatedtoggle()"></div>
                </div>
                <div class="text">STOP</div>
            </div>
        </div>

        @if (session('success'))
            <div class="">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="">
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
                @foreach ($sensors as $sensor)
                    <div class="grid-item">{{ $sensor->name }} <span class="status live">Live</span> <span
                            class="aqi-value" id="aqi-{{ $sensor->id }}">{{ $sensor->aqi }}</span></div>
                @endforeach
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

                            <button type="submit" class="delete-btn">Add Location</button>
                        </form>
                    </div>
                    <div class="sensor-delete">
                        <h2>Delete Sensors</h2>
                        <form method="POST" action="/delete-location">
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

        <!-- toggle button -->
    
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const toggle = document.querySelector(".toggle");
                const text = document.querySelector(".text");

            toggle.addEventListener("click", function () {
                toggle.classList.toggle("active");
                text.textContent = toggle.classList.contains("active") ? "START" : "STOP";
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
        </script>
    </body>
</x-layout>
