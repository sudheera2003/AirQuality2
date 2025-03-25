function updateAQI() {
    const sensorIds = window.sensorIds; // Accessing data passed from Blade
    sensorIds.forEach(sensorId => {
        fetch(`/update-aqi/${sensorId}`) // Ensure this matches the route in routes/web.php
            .then(response => response.json())
            .then(data => {
                const aqiElement = document.getElementById('aqi-' + sensorId);
                if (aqiElement) {
                    aqiElement.textContent = data.aqi; // Update the AQI value in the HTML
                }
            })
            .catch(error => console.error('Error fetching AQI data:', error));
    });
}

// Update AQI
setInterval(updateAQI, 2000);
