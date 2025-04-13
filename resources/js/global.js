let aqiInterval = null;
let isAQIUpdating = false;

// Function to update AQI values
function updateAQI() {
    if (!isAQIUpdating) return;

    const sensorIds = window.sensorIds || [];
    sensorIds.forEach(sensorId => {
        fetch(`/update-aqi/${sensorId}`)
            .then(response => response.json())
            .then(data => {
                const aqiElement = document.getElementById('aqi-' + sensorId);
                if (aqiElement) {
                    aqiElement.textContent = data.aqi;
                }
            })
            .catch(error => console.error('Error fetching AQI data:', error));
    });
}

// Function to start AQI updates
window.startAQIUpdates = function () {
    if (!aqiInterval) {
        isAQIUpdating = true;
        sessionStorage.setItem("aqiUpdating", "true");
        aqiInterval = setInterval(updateAQI, 600000);
    }
};

// Function to stop AQI updates
window.stopAQIUpdates = function () {
    isAQIUpdating = false;
    sessionStorage.setItem("aqiUpdating", "false");
    clearInterval(aqiInterval);
    aqiInterval = null;
};

// Restore AQI state when the page loads
document.addEventListener("DOMContentLoaded", function () {
    const savedState = sessionStorage.getItem("aqiUpdating");
    if (savedState === "true") {
        startAQIUpdates();
    } else {
        stopAQIUpdates();
    }
});
