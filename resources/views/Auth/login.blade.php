<x-layout>

    <head>
        @vite('resources/css/login.css')
        @vite('resources/css/flash.css')
        <title>Admin Login</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    </head>

    <body>
        <div class="container">
            <div class="login-box">
                <h1>Admin Login</h1>
                <p>Only authorized administrators can log in. If you do not have access, please contact support.</p>
                    <form action='{{ route('login') }}' method="POST">
                        @csrf
                        <div class="input-box">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name='email' placeholder="Enter your email" value='{{ old('email') }}' required>
                        </div>
                        <div class="input-box">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="password" name='password' placeholder="Enter your password" required>
                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                        </div>
                        <button class="Login-btn" type="submit">Login</button>
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
                <a href="">Forgot your password? <span class="reset-txt">Reset Password</span></a><br><br>
                <a href="{{ route('home') }}"><span class="back-txt">Go back?</span></a>
            </div>
        </div>

           
        </div>
        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

        <script>
            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");

            togglePassword.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    togglePassword.classList.remove("fa-eye");
                    togglePassword.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    togglePassword.classList.remove("fa-eye-slash");
                    togglePassword.classList.add("fa-eye");
                }
            });
        </script>
    </body>
</x-layout>
