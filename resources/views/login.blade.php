@extends('layouts.app')

@section('content')

<h1>Iniciar sesión</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="user_name">Nombre de usuario</label>
        <input type="text" id="user_name" name="user_name" class="form-control" value="{{ old('user_name') }}">
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Recordarme</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Iniciar sesión</button>

    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
</form>

@endsection
