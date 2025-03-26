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
                background-color: rgba(0,0,0,0.5);
            }
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border-radius: 8px;
                width: 400px;
                max-width: 80%;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        </style>
    </head>

    <body>
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
                <div class="toggle">
                    <div class="toggle-btn"></div>
                </div>
                <div class="text">STOP</div>
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
                        <form class="input-group" method="POST" action="">
                            @csrf
                            <label>Sensor ID</label>
                            <input type="text" name="id" required>

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


                            <button type="submit" class="delete-btn">Edit Changes</button>
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
                                updateSafeLevels(sensor.aqi, sensor.id);
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

            function updateSafeLevels(aqi, id) {
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
                } else if (aqi <= 300) {
                    levelText = "Very Unhealthy";
                    colorText = "red";
                } else {
                    levelText = "Hazardous";
                    colorText = "darkred";
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
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                                    showMessage('Error', 'Error: ' + error.message, 'error');
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
                                    showMessage('Success', 'Admin updated successfully!', 'success');
                                } else {
                                    showMessage('Error', 'Error updating admin: ' + data.message, 'error');
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

        </script>
    </body>
</x-layout>
