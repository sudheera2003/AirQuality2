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
                        <input type="email" name='email' placeholder="Enter your email" value='{{ old('email') }}'
                            required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name='name' placeholder="Enter your name" value='{{ old('name') }}'
                            required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name='password' placeholder="Enter your password" required>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name='password_confirmation' placeholder="Confirm your password"
                            required>
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
    </body>
</x-layout>
