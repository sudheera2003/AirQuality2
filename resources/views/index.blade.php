<x-layout>

    <head>
        @vite('resources/css/home.css')
        <script src="https://code.jscharting.com/latest/jscharting.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3NJJqTZY-qtkJ9JiuIqNu2ZS9tBjCpoI&callback=initMap" async
            defer></script>

        <title>Air Quality - Colombo</title>
        @vite('resources/js/global.js')
    </head>

    <body>
        <div class="container">
            <div class="top-section">
                <p class="txt-1">Sri Lanka / <span class="col-blk">Colombo</span></p>
                <h1>Air quality in Colombo</h1>
                <p class="txt-2">Air quality index (AQI) in Colombo 09:42, Mar 14</p>
            </div>

            <div class="mid-section">
                <div class="left-container">
                    <div id="map" style="width: 1000px; height: 550px;"></div>
                </div>
                <div class="right-container">
                    <p class="loc"><i class="fa-solid fa-location-dot"></i> Location</p>
                    <p class="txt-1">Sri Lanka / Colombo /<span class="col-blk" id="sensor-name"> Select a
                            location</span></p>
                    <hr class="divider">
                    <p class="L-p"><span class="live-indicator"></span>Live</p>
                    <div id="chartDiv" style="max-width: 740px;height: 330px;margin: 0px auto"></div>
                    <div class="flex-it">
                        <div class="populated-section">
                            <p class="title-mini">Most Populated Divisions</p>
                            <p class="col-txt-1">Colombo 04 <span class="aqi-box aqi-high">104</span></p>
                            <p class="col-txt-1">Colombo 07 <span class="aqi-box aqi-medium">82</span></p>
                            <p class="col-txt-1">Colombo 10 <span class="aqi-box aqi-low">55</span></p>
                        </div>
                        <div>
                            <button class="check-btn">Check Historical Data</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-section">
                <div class="right-msg-field">
                    <h3>Clean Air!</h3>
                    <p>You can go outside without fear and enjoy the day</p>
                    <img src="{{ Vite::asset('resources/assets/images/air.png') }}" alt="Logo">
                </div>
            </div>
        </div>

        <script>
            window.sensorIds = @json($sensors->pluck('id')); // Passing sensor IDs from Blade to JavaScript
            let currentMarker = null; // To keep track of the current marker
            let currentInfoWindow = null; // To keep track of the current info window
            let activeSensorId = null; // To track the active sensor's ID (the one clicked by the user)
            let aqiUpdateInterval = null; // Variable to hold the interval ID

            // Function to fetch the latest AQI data from the server
            function updateAQIFromServer(sensorId) {
                fetch(`/update-aqi/${sensorId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.aqi) {
                            // Update AQI gauge with new value
                            initializeAQIGauge(data.aqi);

                            // Update the AQI value displayed in the info window
                            document.getElementById('aqi-' + sensorId).textContent = data.aqi;

                            // Dynamically update the AQI status (Good, Moderate, etc.) based on the AQI value
                            const aqiCategory = getAqiCategory(data.aqi);
                            document.getElementById('aqi-status-' + sensorId).textContent = aqiCategory.category;
                            document.getElementById('aqi-status-' + sensorId).style.color = aqiCategory.color;
                        } else {
                            console.error('Error fetching AQI data:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching AQI data:', error);
                    });
            }

            // Function to initialize the AQI gauge with the latest value (default 0)
            function initializeAQIGauge(aqiValue = 0) {
                JSC.chart('chartDiv', {
                    debug: false,
                    legend_visible: false,
                    defaultTooltip_enabled: false,
                    xAxis_spacingPercentage: 0.4,
                    yAxis: [{
                        id: 'ax1',
                        customTicks: [0, 100, 200, 300, 400, 500],
                        line: {
                            width: 10,
                            color: 'smartPalette:pal1'
                        },
                        scale_range: [0, 500]
                    }],
                    defaultSeries: {
                        type: 'gauge column roundcaps',
                        shape: {
                            label: {
                                text: '%max',
                                align: 'center',
                                verticalAlign: 'middle',
                                style_fontSize: 28
                            }
                        }
                    },
                    series: [{
                        type: 'column roundcaps',
                        name: 'AQI',
                        yAxis: 'ax1',
                        palette: {
                            id: 'pal1',
                            pointValue: '%yValue',
                            ranges: [{
                                    value: 0,
                                    color: '#21D683'
                                },
                                {
                                    value: 100,
                                    color: '#77E6B4'
                                },
                                {
                                    value: 200,
                                    color: '#FFD221'
                                },
                                {
                                    value: 300,
                                    color: '#FF9B21'
                                },
                                {
                                    value: 400,
                                    color: '#FF5353'
                                },
                                {
                                    value: 500,
                                    color: '#D60000'
                                }
                            ]
                        },
                        points: [
                            ['AQI', aqiValue]
                        ]
                    }]
                });
            }

            // Function to categorize AQI based on color and category
            function getAqiCategory(aqiValue) {
                if (aqiValue >= 0 && aqiValue <= 50) {
                    return {
                        category: 'Good',
                        color: 'green'
                    };
                } else if (aqiValue >= 51 && aqiValue <= 100) {
                    return {
                        category: 'Moderate',
                        color: 'yellow'
                    };
                } else if (aqiValue >= 101 && aqiValue <= 150) {
                    return {
                        category: 'Unhealthy for Sensitive Groups',
                        color: 'orange'
                    };
                } else if (aqiValue >= 151 && aqiValue <= 200) {
                    return {
                        category: 'Unhealthy',
                        color: 'red'
                    };
                } else if (aqiValue >= 201 && aqiValue <= 300) {
                    return {
                        category: 'Very Unhealthy',
                        color: 'purple'
                    };
                } else {
                    return {
                        category: 'Hazardous',
                        color: 'darkred'
                    };
                }
            }

            // Initialize map and fetch sensor locations
            function initMap() {
                const colombo = {
                    lat: 6.9271,
                    lng: 79.8612
                };
                const map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: colombo
                });

                // Loop through sensors and add markers for those with status_id = 1
                const sensors = @json($sensors); // Ensure that sensors include the status_id field
                sensors.filter(location => location.status_id === 1).forEach(location => {
                    const aqiCategory = getAqiCategory(location.aqi);

                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(location.lat),
                            lng: parseFloat(location.lng)
                        },
                        map: map,
                        title: location.name
                    });

                    const infowindow = new google.maps.InfoWindow({
                        content: `<div style="font-size: 18px; font-weight: bold;" id="sensor-name-${location.id}">${location.name}</div>
                      <br>AQI: <span id="aqi-${location.id}">${location.aqi}</span><br>
                      Status: <span id="aqi-status-${location.id}" style="color: ${aqiCategory.color}; font-weight: bold;">${aqiCategory.category}</span>
                    <div id="aqi-chart-${location.id}" style="width: 300px; height: 150px;"></div>` // Chart container
                    });

                    // Click event to open info window and update AQI gauge
                    marker.addListener('click', () => {
                        // If an InfoWindow is already open, close it
                        if (currentInfoWindow) {
                            currentInfoWindow.close();
                        }

                        // Open the clicked marker's InfoWindow
                        infowindow.open(map, marker);

                        // Update the current InfoWindow
                        currentInfoWindow = infowindow;

                        // Set this marker as the active one
                        currentMarker = marker;

                        // Set the active sensor ID
                        activeSensorId = location.id;

                        // Update the selected sensor name in the DOM
                        document.getElementById('sensor-name').textContent = location
                            .name; // Update the sensor name here

                        // Initialize AQI gauge with the current sensor's AQI value
                        initializeAQIGauge(location.aqi);

                        // Fetch historical AQI data for the last 24 hours
                        fetch(`/sensor/${location.id}/historical-aqi`)
                            .then(response => response.json())
                            .then(data => {
                                // Extract the last 24 hours' AQI values and recorded times
                                const aqiValues = data.map(item => item.aqi_value);
                                const times = data.map(item => new Date(item.recorded_at)
                                    .toLocaleTimeString());

                                // Create a small chart for the historical AQI data inside the InfoWindow
                                JSC.chart(`aqi-chart-${location.id}`, {
                                    type: 'line',
                                    title_label_text: '24-Hour AQI Trend',
                                    legend_visible: false,
                                    xAxis: {
                                        categories: times,
                                    },
                                    yAxis: {
                                        title_label_text: 'AQI',
                                        customTicks: [0, 100, 200, 300, 400, 500]
                                    },
                                    series: [{
                                        name: 'AQI',
                                        points: aqiValues.map((value, index) => [times[
                                            index], value])
                                    }]
                                });
                            })
                            .catch(error => console.error('Error fetching historical AQI data:', error));

                        // Periodically update AQI data only if AQI updates are allowed
                        if (sessionStorage.getItem("aqiUpdating") === "true") {
                            if (aqiUpdateInterval) {
                                clearInterval(aqiUpdateInterval);
                            }
                            aqiUpdateInterval = setInterval(() => {
                                updateAQIFromServer(location.id);
                            }, 2000);
                        }
                    });

                });

                // Click event on the map to reset the marker and AQI
                google.maps.event.addListener(map, 'click', () => {
                    // Close any open InfoWindow
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }

                    // Reset AQI gauge to 0 when clicking on the map
                    initializeAQIGauge(0);

                    // Reset the sensor name to default
                    document.getElementById('sensor-name').textContent = ' Select a location';

                    // Clear the active sensor ID
                    activeSensorId = null;

                    // Clear the existing interval
                    if (aqiUpdateInterval) {
                        clearInterval(aqiUpdateInterval);
                        aqiUpdateInterval = null; // Reset interval ID
                    }
                });

                // Initialize meter with 0 on first load
                initializeAQIGauge(0);
            }


            // Add event listener for clicks anywhere on the page
            document.body.addEventListener('click', (event) => {
                // If the click is outside the map and marker
                if (!event.target.closest('#map') && currentMarker) {
                    // Reset the map and gauge
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }
                    initializeAQIGauge(0);
                    document.getElementById('sensor-name').textContent = ' Select a location';
                    activeSensorId = null;
                    if (aqiUpdateInterval) {
                        clearInterval(aqiUpdateInterval);
                        aqiUpdateInterval = null;
                    }
                    currentMarker = null;
                }
            });
        </script>

</x-layout>
