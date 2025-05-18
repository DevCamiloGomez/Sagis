<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Traits\HasRoles;

/**
 * Clase User - Modelo principal para usuarios del sistema
 * 
 * Esta clase maneja la autenticación y autorización de usuarios en el sistema.
 * Extiende de Authenticatable para proporcionar funcionalidades de autenticación de Laravel.
 * 
 * Características principales:
 * - Autenticación de usuarios
 * - Gestión de tokens API
 * - Sistema de roles y permisos
 * - Notificaciones
 * - Relación con datos personales
 * 
 * @property int $id ID único del usuario
 * @property int $person_id ID de la persona relacionada
 * @property string $code Código único del usuario
 * @property string $email Email del usuario
 * @property string $password Contraseña encriptada
 * @property datetime $email_verified_at Fecha de verificación del email
 * 
 * Documentado por: Camilo Gomez
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'code',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set the 
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }


    /**
     * @return \App\Models\Person
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
