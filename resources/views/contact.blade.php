<x-layout>

    <head>
        @vite('resources/css/contact.css')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Contact</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

            /* Loading Spinner Styles */
            .loading-spinner {
                position: fixed;
                z-index: 2000;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .spinner {
                border: 4px solid #f3f3f3;
                border-top: 4px solid rgb(0, 247, 255);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 2s linear infinite;
            }

            .spinner-main{
                display: none;
                position: fixed;
                z-index: 1001;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <body>
    <div class='spinner-main' id="spinner-main">
        <div id="loading-spinner" class="loading-spinner">
            <div class="spinner"></div>
        </div>
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

        {{-- ------------------------------------------------------------------------------------------------------------- --}}

        <div class="top-section">
            <h1>Contact Us</h1>
        </div>
        <div class="flex-container">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <p class="sub-text">Say something to start a live chat!</p>
                    <div class="info-item">
                        <p><i class="fa-solid fa-phone-volume" style="color: #ffffff;"></i></p>
                        <p>+94 077 4545 699</p>
                    </div>
                    <div class="info-item">
                        <p><i class="fa-solid fa-envelope" style="color: #ffffff;"></i></p>
                        <p>ColomboAQI@gmail.com</p>
                    </div>
                    <div class="info-item">
                        <p><i class="fa-solid fa-location-dot" style="color: #ffffff;"></i></p>
                        <p> 132 Dartmouth Street g-watha, <br> Mahawatha 02156 Colombo</p>
                    </div>
                </div>

                <form id="contact-form">
                    <div class="form-group">
                        <div>
                            <label>First Name</label>
                            <input type="text" name="first_name" id="first_name" placeholder="Enter First Name">
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="last_name" placeholder="Enter Last Name">
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" id="email" placeholder="example@gmail.com">
                        </div>
                        <div>
                            <label>Phone Number</label>
                            <input type="text" name="phone" id="phone" placeholder="+94 77 2323 789">
                        </div>
                    </div>

                    <div class="subject-group">
                        <label>Select Subject</label>
                        <div class="radio-group">
                            <label><input type="radio" name="subject" value="Request a sensor" checked> Request a
                                sensor</label>
                            <label><input type="radio" name="subject" value="Technical Support"> Technical
                                Support</label>
                            <label><input type="radio" name="subject" value="Collaboration Request"> Collaboration
                                Request</label>
                        </div>
                    </div>

                    <div class="message-group">
                        <label>Message</label>
                        <textarea name="message" id="message" placeholder="Write your message.."></textarea>
                    </div>

                    <button type="submit" class="send-btn">Send Message</button>
                </form>

                <p id="response-message" style="color: white; display: none;"></p>
            </div>
        </div>

        <script>
            document.querySelector(".send-btn").addEventListener("click", function(event) {
                event.preventDefault(); // Prevent default form submission

                // Show the spinner
                document.getElementById("spinner-main").style.display = "block";

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                if (!csrfToken) {
                    showModal("Error", "CSRF token missing!", "error");
                    document.getElementById("spinner-main").style.display = "none"; // Hide spinner
                    return;
                }

                const data = {
                    first_name: document.getElementById("first_name").value,
                    last_name: document.getElementById("last_name").value,
                    email: document.getElementById("email").value,
                    phone: document.getElementById("phone").value,
                    subject: document.querySelector("input[name='subject']:checked").value,
                    message: document.getElementById("message").value,
                };

                fetch("/contact/send", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })
                    .then(response => {
                        // Show success modal
                        showModal("Success", response.success, "success");
                        // Clear form on success
                        document.getElementById("contact-form").reset();
                    })
                    .catch(error => {
                        // Show error modal
                        showModal("Error", "Failed to send message. Please try again.", "error");
                        console.error("Error:", error);
                    })
                    .finally(() => {
                        document.getElementById("spinner-main").style.display = "none";

                        document.getElementById("messageModal").style.display = "block";
                    });
            });

            // Function to show the modal
            function showModal(title, message, type) {
                const modal = document.getElementById("messageModal");
                const modalTitle = document.getElementById("modalTitle");
                const modalBody = document.getElementById("modalBody");

                modalTitle.textContent = title;
                modalBody.textContent = message;

                if (type === "success") {
                    modalTitle.style.color = "#4CAF50";
                } else {
                    modalTitle.style.color = "#f44336";
                }

                modal.style.display = "block";
            }

            // Close the modal and reset form
            document.getElementById("modalOkBtn").addEventListener("click", function() {
                // Hide the modal
                document.getElementById("messageModal").style.display = "none";

                document.getElementById("contact-form").reset();
            });
        </script>

    </body>
</x-layout>
