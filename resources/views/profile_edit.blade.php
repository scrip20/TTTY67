<form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
    </div>
    <div>
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>
    <div>
        <label for="address">Dirección:</label>
        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
    </div>
    <div>
        <label for="password">Nueva Contraseña:</label>
        <input type="password" id="password" name="password">
        <small>Dejar en blanco para no cambiar la contraseña.</small>
    </div>
    <button type="submit">Actualizar Perfil</button>
</form>