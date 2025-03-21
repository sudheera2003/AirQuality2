<x-layout>
    <head>
        @vite('resources/css/contact.css')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <p><i class="fa-solid fa-phone-volume" style="color: #ffffff;"></i></p><p>+1012 3456 789</p>
                    </div>
                    <div class="info-item">
                        <p><i class="fa-solid fa-envelope" style="color: #ffffff;"></i></p><p> demo@gmail.com</p>
                    </div>
                    <div class="info-item">
                        <p><i class="fa-solid fa-location-dot" style="color: #ffffff;"></i></p><p> 132 Dartmouth Street Boston, <br> Massachusetts 02156 United States</p>
                    </div>
                </div>
            
                <div class="contact-form">
                    <div class="form-group">
                        <div>
                            <label>First Name</label>
                            <input type="text" placeholder="John">
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input type="text" placeholder="Doe">
                        </div>
                    </div>
            
                    <div class="form-group">
                        <div>
                            <label>Email</label>
                            <input type="email" placeholder="example@gmail.com">
                        </div>
                        <div>
                            <label>Phone Number</label>
                            <input type="text" placeholder="+1 012 3456 789">
                        </div>
                    </div>
            
                    <div class="subject-group">
                        <label>Select Subject?</label>
                        <div class="radio-group">
                            <label><input type="radio" name="subject" checked> General Inquiry</label>
                            <label><input type="radio" name="subject"> General Inquiry</label>
                            <label><input type="radio" name="subject"> General Inquiry</label>
                        </div>
                    </div>
            
                    <div class="message-group">
                        <label>Message</label>
                        <textarea placeholder="Write your message.."></textarea>
                    </div>
            
                    <button class="send-btn">Send Message</button>
                </div>
            </div>
        </div>
    </body>
</x-layout>