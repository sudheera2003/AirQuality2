<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Air Quality - Colombo</title>
    @vite('resources/css/Header.css')
    @vite('resources/css/footer.css')
</head>
<body>
    <header>
    <img src="{{ Vite::asset('resources/assets/images/Logo.svg') }}" alt="Logo">
        
        <div class="search-container">
            <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="text" class="search-input" placeholder="Search here">
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Historical Data</a></li>
                <li><a href="#">About AQI</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Login</a></li>
            </ul>
        </nav>
    </header>

        {{$slot}}

    <footer>
        <div class="footer-left">
        <img src="{{ Vite::asset('resources/assets/images/Logo - white.svg') }}" alt="Logo">
          <p>Copyright 2025 AQI Colombo<br>All rights reserved</p>
        </div>
        <div class="footer-center">
          <a href="#">Home</a>
          <a href="#">Historical Data</a>
          <a href="#">About AQI</a>
          <a href="#">Contact</a>
        </div>
        <div class="footer-right">
          <p class="title">Contact Us</p>
          <p>Call Us: <span>031 45 235 95</span><p>
          <p>Email Us: <span>ColomboAQI@gmail.com</span></p>
        </div>
      </footer>
</body>
</html>
