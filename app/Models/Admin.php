<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable; // Added Notifiable import

/**
 * Clase Admin - Modelo para gestión de administradores del sistema
 * 
 * Esta clase maneja los usuarios administradores del sistema,
 * incluyendo su autenticación y permisos especiales.
 * 
 * Características principales:
 * - Autenticación de administradores
 * - Gestión de roles y permisos
 * - Relación con datos personales
 * - Manejo de tokens API
 * 
 * @property int $id ID único del administrador
 * @property int $person_id ID de la persona relacionada
 * @property string $email Email del administrador
 * @property string $password Contraseña encriptada
 * @property datetime $email_verified_at Fecha de verificación del email
 * 
 * @property-read Person $person Persona relacionada
 * @property-read Collection|Role[] $roles Roles asignados
 * 
 * Documentado por: Camilo Gomez
 */
class Admin extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The guard name for the model.
     *
     * @var string
     */
    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['person_id', 'email', 'password'];



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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\AdminResetPassword($token));
    }
}
