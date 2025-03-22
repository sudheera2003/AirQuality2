<x-layout>

    <head>
        @vite('resources/css/login.css')
        @vite('resources/css/flash.css')
        <title>Admin Register</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    </head>

    <body>
        <div class="container">
            <div class="login-box">
                <h1>Admin Register</h1>
                <p>Only authorized administrators can register. If you do not have access, please contact support.</p>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="input-box">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="name" placeholder="Enter your name" value="{{ old('name') }}" required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm your password" required>
                        <i class="fa-solid fa-eye" id="toggleConfirmPassword"></i>
                    </div>
                    <button class="Login-btn" type="submit">Register</button>
                </form>

            </div>
            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="extra-links">
                <a href="{{ route('home') }}"><span class="back-txt">Go back?</span></a>
            </div>
        </div>
        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

        <script>
            function togglePasswordVisibility(toggleId, inputId) {
                const toggleIcon = document.getElementById(toggleId);
                const passwordInput = document.getElementById(inputId);

                toggleIcon.addEventListener("click", function () {
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        toggleIcon.classList.remove("fa-eye");
                        toggleIcon.classList.add("fa-eye-slash");
                    } else {
                        passwordInput.type = "password";
                        toggleIcon.classList.remove("fa-eye-slash");
                        toggleIcon.classList.add("fa-eye");
                    }
                });
            }

            togglePasswordVisibility("togglePassword", "password");
            togglePasswordVisibility("toggleConfirmPassword", "confirmPassword");
        </script>
    </body>
</x-layout>
