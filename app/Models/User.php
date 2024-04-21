<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo de usuario que extiende de Authenticatable para funcionalidades de autenticación.
 * Utiliza el trait HasFactory para permitir la creación de fábricas de modelos.
 */
class User extends Authenticatable
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', // Nombre de usuario
        'email', // Correo electrónico del usuario
        'password', // Contraseña del usuario
        'character', // Personaje asociado al usuario
        'level', // Nivel del usuario
        'role', // Rol del usuario (ej. 'admin', 'user')
    ];

    /**
     * Relación uno a muchos con Completion.
     * Un usuario puede tener múltiples completions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function completion()
    {
        return $this->hasMany(Completion::class);
    }
    
    /**
     * Relación uno a muchos con Accuracy.
     * Un usuario puede tener múltiples accuracies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accuracy()
    {
        return $this->hasMany(Accuracy::class);
    }
}