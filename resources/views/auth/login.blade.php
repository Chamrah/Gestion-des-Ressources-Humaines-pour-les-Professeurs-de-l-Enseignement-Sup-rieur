@include('components.header')

<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.831)), url('{{ asset("bcg.jpg") }}'),no-repeat;
        font-family: 'Segoe UI', sans-serif;
        background-size: cover;
        background-position: left;
    }

    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        backdrop-filter: blur(2px);
    }

    .login-box {
        background: white;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 450px;
        text-align: center;
    }

    .login-box img {
        width: 100px;
        margin-bottom: 20px;
    }

    .login-box h2 {
        font-size: 22px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .login-box p {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .form-control {
        margin-bottom: 15px;
        height: 45px;
        border-radius: 8px;
    }

    .btn-login {
        background-color: #004d33;
        color: white;
        width: 100%;
        border-radius: 8px;
        padding: 12px;

        font-weight: bold;
    }

    .btn-login:hover {
        background-color: #003322;
        color: white
    }

    .footer-links {
        margin-top: 20px;
        font-size: 14px;
        color: #555;
    }

    .footer-links a {
        color: #2980b9;
        text-decoration: none;
    }
</style>

<div class="login-wrapper">
    <div class="login-box">
        <img class="logo-fssm" src="{{ asset('logo.jpg') }}"  alt="Logo">

        <h2>Bienvenue</h2>
        <p>Connexion à la plateforme RH des enseignants</p>
        
        <form action="{{ url('/import-professeurs') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Importer Excel</button>
        </form>
        
        <a href="{{ url('/export-professeurs') }}">Exporter vers Excel</a>
        
        <form action="{{ route('auth') }}" method="post">
            @csrf

            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Identifiant" value="{{ old('username') }}">
            @error('username')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-login mt-3">Connexion</button>
        </form>

        @if ($errors->has('credentials'))
            <div class="alert alert-danger mt-3">
                {{ $errors->first('credentials') }}
            </div>
        @endif

       
    </div>
</div>

@include('components.footer')
