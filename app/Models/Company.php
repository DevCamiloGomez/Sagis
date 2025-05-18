<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase Company - Modelo para gestión de empresas
 * 
 * Esta clase maneja la información de las empresas donde
 * trabajan o han trabajado los graduados.
 * 
 * Características principales:
 * - Gestión de información empresarial
 * - Relación con ubicaciones geográficas
 * - Seguimiento de empleados graduados
 * 
 * @property int $id ID único de la empresa
 * @property int $city_id ID de la ciudad donde se ubica
 * @property string $name Nombre de la empresa
 * @property string $email Email de contacto
 * @property string $phone Teléfono de contacto
 * @property string $address Dirección física
 * 
 * @property-read City $city Ciudad donde se ubica
 * @property-read Collection|PersonCompany[] $personCompanies Relaciones con graduados
 * 
 * Documentado por: Camilo Gomez
 */
class Company extends Model
{
    use HasFactory;


/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = ['city_id', 'name', 'address', 'email', 'phone'];





 /** Relation Methods */
 public function city()
 {
    return $this->belongsTo(City::class);
 }

 public function personCompanies()
 {
    return $this->hasMany(PersonCompany::class);
 }
}