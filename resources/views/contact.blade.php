<x-layout>

    <head>
        @vite('resources/css/contact.css')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Contact</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </head>

    <body>
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
            document.querySelector(".send-btn").addEventListener("click", function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                if (!csrfToken) {
                    console.error("CSRF token missing!");
                    return;
                }

                const data = {
                    first_name: document.querySelector("input[placeholder='Enter First Name']").value,
                    last_name: document.querySelector("input[placeholder='Enter Last Name']").value,
                    email: document.querySelector("input[placeholder='example@gmail.com']").value,
                    phone: document.querySelector("input[placeholder='+94 77 2323 789']").value,
                    subject: document.querySelector("input[name='subject']:checked").nextSibling.textContent.trim(),
                    message: document.querySelector("textarea").value,
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
                    .then(data => {
                        alert(data.success);
                    })
                    .catch(error => console.error("Error sending message:", error));
            });
        </script>

    </body>
</x-layout>
