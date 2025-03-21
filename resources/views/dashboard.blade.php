<x-layout>
@vite('resources/css/dashboard.css')
<script src="https://code.jscharting.com/latest/jscharting.js"></script>
<head> 
    <title>Air Quality - Admin Dashboard</title>
</head>
    
<body>
    <div class="dashboard-container">
        <div class="top-section">
            <p class="txt-1">Sri lanka / <span class="col-blk">Colombo</span></p>
            <h1>Live Sensors</h1>
            <p class="txt-2">Air quality index (AQI) in Colombo 09:42, Mar 14</p>
        </div>
        <div class="grid-container">
            <div class="grid-item">Colombo 1 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 2 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 3 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 4 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 5 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 6 <span class="status na">N/A</span></div>
            <div class="grid-item">Colombo 7 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 8 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 9 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 10 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 11 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 12 <span class="status na">N/A</span></div>
            <div class="grid-item">Colombo 13 <span class="status live">Live</span></div>
            <div class="grid-item">Colombo 14 <span class="status live">Live</span></div>
        </div>
        
        <div class="bottom-section">
            <h1 class="title">Manage Sensors</h1>

            <div class="sensor-container">
                <!-- Add Sensor Form -->
                <div class="sensor-form">
                    <h2>Add Sensors</h2>
                    <div class="input-group">
                        <label>Sensor Name</label>
                        <input type="text">
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <label>Sensor ID</label>
                            <input type="text">
                        </div>
                        <div class="input-group">
                            <label>Connection Type</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <label>Longitude</label>
                            <input type="text">
                        </div>
                        <div class="input-group">
                            <label>Latitude</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Status</label>
                        <select>
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                    <button class="add-btn">Add Sensor</button>
                </div>

                <!-- Delete Sensor Form -->
                <div class="sensor-delete">
                    <h2>Delete Sensors</h2>
                    <div class="input-group">
                        <label>Sensor ID</label>
                        <input type="text">
                    </div>
                    <button class="delete-btn">Delete Sensor</button>
                </div>
            </div>
        </div>
    </div>

</body>
</x-layout>
