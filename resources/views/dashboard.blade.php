<x-layout>
    @vite('resources/css/dashboard.css')
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Air Quality - Admin Dashboard</title>
        @vite('resources/js/global.js')
        <style>
            /* Modal styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border-radius: 8px;
                width: 400px;
                max-width: 80%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }

            .modal-title {
                font-size: 1.2rem;
                font-weight: bold;
            }

            .modal-body {
                margin-bottom: 20px;
            }

            .modal-footer {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }

            .modal-btn {
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .modal-btn-primary {
                background-color: #4CAF50;
                color: white;
            }

            .modal-btn-secondary {
                background-color: #f44336;
                color: white;
            }

            .modal-success .modal-title {
                color: #4CAF50;
            }

            .modal-error .modal-title {
                color: #f44336;
            }

            .modal-confirm .modal-title {
                color: #2196F3;
            }

            .notification-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .notification {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 300px;
                padding: 15px;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                background-color: #fff;
                border-left: 5px solid #4CAF50;
                transform: translateX(100%);
                opacity: 0;
                transition: all 0.3s ease;
            }

            .notification.show {
                transform: translateX(0);
                opacity: 1;
            }

            .notification.purple {
                border-left-color: purple;
            }

            .notification.red {
                border-left-color: red;
            }

            .notification.darkred {
                border-left-color: darkred;
            }

            .notification-content {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .notification-title {
                font-weight: bold;
                margin-bottom: 5px;
                font-size: 16px;
            }

            .notification-message {
                font-size: 14px;
                color: #555;
            }

            .notification-close {
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
                color: #888;
                margin-left: 10px;
                padding: 0 5px;
            }

            .notification-close:hover {
                color: #333;
            }
        </style>
    </head>

    <body>
        <!-- Notification Container -->
        <div id="notification-container" class="notification-container">
            <!-- Notifications will be added here dynamically -->
        </div>

        <!-- Notification Template (Hidden) -->
        <div id="notification-template" class="notification" style="display: none;">
            <div class="notification-content">
                <span class="notification-title"></span>
                <span class="notification-message"></span>
            </div>
            <button class="notification-close">&times;</button>
        </div>

        <!-- Modal -->
        <div id="messageModal" class="modal">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <span class="modal-title" id="modalTitle">Message</span>
                </div>
                <div class="modal-body" id="modalBody">
                    Message content goes here
                </div>
                <div class="modal-footer">
                    <button class="modal-btn modal-btn-primary" id="modalOkBtn">OK</button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmModal" class="modal">
            <div class="modal-content modal-confirm">
                <div class="modal-header">
                    <span class="modal-title">Confirm Action</span>
                </div>
                <div class="modal-body" id="confirmBody">
                    Are you sure you want to perform this action?
                </div>
                <div class="modal-footer">
                    <button class="modal-btn modal-btn-secondary" id="confirmCancelBtn">Cancel</button>
                    <button class="modal-btn modal-btn-primary" id="confirmOkBtn">Confirm</button>
                </div>
            </div>
        </div>

        {{-- ----------------------------------------------------------------------------------- --}}


        <div class="top-flex-section">
            <div class="welcome-container">
                <span class="welcome-msg">Hello, {{ Auth::user()->name }}</span>
            </div>

            <div class="container">
                <h3>ON/OFF Data Simulation</h3>
                <div class="toggle-section">
                    <div class="toggle">
                        <div class="toggle-btn"></div>
                    </div>
                    <div class="text">STOP</div>
                </div>
            </div>

        </div>
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif --}}
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
                <table class="sensor-table">
                    <tr>
                        <th>Sensor ID</th>
                        <th>Sensor Name</th>
                        <th>AQI Value</th>
                        <th>Status</th>
                        <th style="width:550px;">Safe Levels</th>
                    </tr>
                    @foreach ($sensors as $sensor)
                        <tr>
                            <td>{{ $sensor->id }}</td>
                            <td>{{ $sensor->name }}</td>
                            <td>
                                @if ($sensor->status_id == 1)
                                    <span id="aqi-{{ $sensor->id }}">{{ $sensor->aqi }}</span>
                                @else
                                    <span>0</span>
                                @endif
                            </td>
                            <td>
                                @if ($sensor->status_id == 1)
                                    <span class="status live">Live</span>
                                @else
                                    <span class="status na">Offline</span>
                                @endif
                            </td>
                            <td>
                                @if ($sensor->status_id == 1)
                                    <span class="text-col" id="safe-{{ $sensor->id }}"></span>
                                @else
                                    <span class="text-col" style="color:gray">Offline</span>
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
                        <form class="input-group" method="POST" action="{{ route('sensor.store') }}">
                            @csrf
                            <label>Location Name</label>
                            <input type="text" name="name" required>

                            <label>Latitude</label>
                            <input type="text" name="lat" required>

                            <label>Longitude</label>
                            <input type="text" name="lng" required>

                            <label>Status</label>
                            <select name="status_id" required>
                                <option value="">Select Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->status }}</option>
                                @endforeach
                            </select>


                            <button type="submit" class="delete-btn">Add Location</button>
                        </form>
                    </div>
                    <div class="sensor-delete">
                        <h2>Delete Sensors</h2>
                        <form class="input-group" method="POST" action="{{ route('sensor.destroy') }}">
                            @csrf
                            <label>Sensor ID</label>
                            <input type="text" name="id" required>
                            <button type="submit" class="delete-btn">Delete Sensor</button>
                        </form>
                    </div>
                </div>
                <div class="sensor-form">
                    <h2>Edit Sensors</h2>
                    <form class="input-group" id="editSensorForm">
                        @csrf
                        @method('PUT')

                        <label>Sensor ID</label>
                        <div style="display: flex; align-items: center;">
                            <input type="text" name="id" id="sensor_id" required
                                onchange="fetchSensorData(this.value)">
                            <div id="sensorSpinner" class="loading-spinner" style="display: none;"></div>
                        </div>

                        <label>Location Name</label>
                        <input type="text" name="name" id="sensor_name" required>

                        <label>Latitude</label>
                        <input type="text" name="lat" id="sensor_lat" required>

                        <label>Longitude</label>
                        <input type="text" name="lng" id="sensor_lng" required>

                        <label>Status</label>
                        <select name="status_id" id="sensor_status" required>
                            <option value="">Select Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->status }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="delete-btn" onclick="updateSensor()">Save Changes</button>
                    </form>
                </div>
            </div>

            <div class="admin-section">
                <h1 class="S-title">Manage Admins</h1>
                <div class="sensor-form">
                    <h2>Create Admin</h2>
                    <a href="{{ route('dashboard.register') }}"><button class="r-btn">Register Admin</button></a>
                </div>
                <div>
                    <div class="sensor-form">
                        <h2>Edit/Remove Admin</h2>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr data-id="{{ $admin->id }}">
                                    <td>{{ $admin->id }}</td>
                                    <td contenteditable="true" class="editable name">{{ $admin->name }}</td>
                                    <td contenteditable="true" class="editable email">{{ $admin->email }}</td>
                                    <td style="width: 400px;">
                                        <button class="save-btn">Save</button>
                                        <form method="POST" action="{{ route('admin.destroy', $admin->id) }}"
                                            class="delete-admin-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-admin-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggle = document.querySelector(".toggle");
                const text = document.querySelector(".text");

                // Check stored session state and update the toggle button
                const savedState = sessionStorage.getItem("aqiUpdating");
                if (savedState === "true") {
                    toggle.classList.remove("active");
                    text.textContent = "STOP";
                } else {
                    toggle.classList.add("active");
                    text.textContent = "START";
                }

                // Handle toggle button click
                toggle.addEventListener("click", function() {
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
                            const safeElement = document.getElementById('safe-' + sensor.id);
                            if (aqiElement && safeElement) {
                                aqiElement.textContent = sensor.aqi; // Update the AQI value
                                updateSafeLevels(sensor.aqi, sensor.id, sensor.name);
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
                    element.style.transition = "opacity 0.5s ease";
                    element.style.opacity = "0";
                    setTimeout(() => element.style.display = "none", 500);
                });
            }, 1500);

            function updateSafeLevels(aqi, id, name) {
                let levelText = "";
                let colorText = "";
                if (aqi <= 50) {
                    levelText = "Healthy";
                    colorText = "green";
                } else if (aqi <= 100) {
                    levelText = "Moderate";
                    colorText = "yellow";
                } else if (aqi <= 150) {
                    levelText = "Unhealthy";
                    colorText = "orange";
                } else if (aqi <= 200) {
                    levelText = "Unhealthy";
                    colorText = "purple";
                    // showNotification('Warning', 'High AQI in '+name, 'purple', 0);
                } else if (aqi <= 300) {
                    levelText = "Very Unhealthy";
                    colorText = "red";
                    // showNotification('Warning', 'High AQI in '+name, 'red', 0);
                } else {
                    levelText = "Hazardous";
                    colorText = "darkred";
                    // showNotification('Warning', 'High AQI in '+name, 'darkred', 0);
                }
                document.getElementById("safe-" + id).textContent = levelText;
                document.getElementById("safe-" + id).style.color = colorText;
            }
            document.addEventListener("DOMContentLoaded", function() {
                // Initialize modals
                const messageModal = document.getElementById('messageModal');
                const confirmModal = document.getElementById('confirmModal');
                const modalOkBtn = document.getElementById('modalOkBtn');
                const confirmOkBtn = document.getElementById('confirmOkBtn');
                const confirmCancelBtn = document.getElementById('confirmCancelBtn');

                // Show message modal
                window.showMessage = function(title, message, type = 'success') {
                    const modalContent = document.getElementById('modalContent');
                    modalContent.className = 'modal-content modal-' + type;
                    document.getElementById('modalTitle').textContent = title;
                    document.getElementById('modalBody').textContent = message;
                    messageModal.style.display = 'block';
                };

                // Close message modal
                modalOkBtn.addEventListener('click', function() {
                    messageModal.style.display = 'none';
                    window.location.reload();
                });

                // Confirmation modal
                window.showConfirm = function(message, callback) {
                    document.getElementById('confirmBody').textContent = message;
                    confirmModal.style.display = 'block';

                    confirmOkBtn.onclick = function() {
                        confirmModal.style.display = 'none';
                        callback(true);
                    };

                    confirmCancelBtn.onclick = function() {
                        confirmModal.style.display = 'none';
                        callback(false);
                    };
                };

                // Close modals when clicking outside
                window.addEventListener('click', function(event) {
                    if (event.target === messageModal) {
                        messageModal.style.display = 'none';
                    }
                    if (event.target === confirmModal) {
                        confirmModal.style.display = 'none';
                    }
                });

                // Replace all alert confirmations with modal
                document.querySelectorAll('.delete-admin-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        showConfirm('Are you sure you want to delete this admin?', function(confirmed) {
                            if (confirmed) {
                                fetch(form.action, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            _method: 'DELETE'
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            form.closest('tr').remove();
                                            showMessage('Success', data.message, 'success');
                                        } else {
                                            showMessage('Error', data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        showMessage('Error', 'Error: ' + error.message,
                                            'error');
                                    });
                            }
                        });
                    });
                });

                // Save Admin with modal feedback
                document.querySelectorAll(".save-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        let row = this.closest("tr");
                        let adminId = row.getAttribute("data-id");
                        let name = row.querySelector(".name").innerText.trim();
                        let email = row.querySelector(".email").innerText.trim();

                        fetch("{{ route('updateAdmin') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    id: adminId,
                                    name: name,
                                    email: email
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showMessage('Success', 'Admin updated successfully!',
                                        'success');
                                } else {
                                    showMessage('Error', 'Error updating admin: ' + data.message,
                                        'error');
                                }
                            })
                            .catch(error => {
                                showMessage('Error', 'Error: ' + error.message, 'error');
                            });
                    });
                });

                @if (session('success'))
                    showMessage('Success', '{{ session('success') }}', 'success');
                @endif
                @if (session('error'))
                    showMessage('Error', '{{ session('error') }}', 'error');
                @endif
            });

            async function fetchSensorData(sensorId) {
                if (!sensorId) return;

                const spinner = document.getElementById('sensorSpinner');
                spinner.style.display = 'inline-block';

                try {
                    const response = await fetch(`sensors/${sensorId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to fetch sensor data');
                    }

                    const data = await response.json();

                    if (!data.success) {
                        throw new Error(data.message || 'Invalid sensor data');
                    }

                    // Fill the form
                    document.getElementById('sensor_name').value = data.data.name || '';
                    document.getElementById('sensor_lat').value = data.data.lat || '';
                    document.getElementById('sensor_lng').value = data.data.lng || '';
                    document.getElementById('sensor_status').value = data.data.status_id || '';

                } catch (error) {
                    showMessage('Error', "Sensor Not Found!", 'error');
                    clearForm();
                } finally {
                    spinner.style.display = 'none';
                }
            }

            function clearForm() {
                document.getElementById('sensor_name').value = '';
                document.getElementById('sensor_lat').value = '';
                document.getElementById('sensor_lng').value = '';
                document.getElementById('sensor_status').value = '';
            }

            async function updateSensor() {
                const formData = {
                    name: document.getElementById('sensor_name').value,
                    lat: document.getElementById('sensor_lat').value,
                    lng: document.getElementById('sensor_lng').value,
                    status_id: document.getElementById('sensor_status').value
                };

                const sensorId = document.getElementById('sensor_id').value;

                try {
                    const response = await fetch(`/sensors/${sensorId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Update failed');
                    }
                    showMessage('Success', 'Sensor updated successfully!', 'success');
                } catch (error) {
                    showMessage('Error', "Failed to update sensor", 'error');
                }
            }

            // Notification function
            function showNotification(title, message, type = 'success', duration = 5000) {
                const container = document.getElementById('notification-container');
                const template = document.getElementById('notification-template');

                // Clone the template
                const notification = template.cloneNode(true);
                notification.id = '';
                notification.style.display = 'flex';
                notification.classList.add(type);

                // Set content
                notification.querySelector('.notification-title').textContent = title;
                notification.querySelector('.notification-message').textContent = message;

                // close handler
                const closeBtn = notification.querySelector('.notification-close');
                closeBtn.addEventListener('click', () => {
                    hideNotification(notification);
                });

                // Add
                container.appendChild(notification);

                // Show animation
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Auto-hide if duration is set
                if (duration > 0) {
                    setTimeout(() => {
                        hideNotification(notification);
                    }, duration);
                }

                return notification;
            }

            // Hide notification with animation
            function hideNotification(notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }

        </script>
    </body>
</x-layout>
