<x-layout>
    <head>
        @vite('resources/css/login.css')
        <title>Admin Register</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    </head>
    <body>
        <div class="container">
            <div class="login-box">
                <h1>Admin Register</h1>
                <p>Only authorized administrators can register. If you do not have access, please contact support.</p>
                <form>
                    <div class="input-box">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" placeholder="Enter your password" required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" placeholder="Confirm your password" required>
                    </div>
                    <a href=""><button class="Login-btn" type="submit">Register</button></a>
                </form>
            </div>

            <div class="extra-links">
                <a href="{{ route('home') }}"><span class="back-txt">Go back?</span></a>
            </div>
        </div>
    </body>
</x-layout>
