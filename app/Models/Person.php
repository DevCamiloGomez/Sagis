<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase Person - Modelo para gestión de información personal
 * 
 * Esta clase maneja toda la información personal de los usuarios del sistema,
 * incluyendo datos básicos, información de contacto y relaciones con otros modelos.
 * 
 * Características principales:
 * - Almacenamiento de datos personales básicos
 * - Gestión de documentos de identidad
 * - Información de contacto
 * - Relaciones con información académica y laboral
 * - Manejo de imágenes de perfil
 * 
 * @property int $id ID único de la persona
 * @property string $name Nombre(s)
 * @property string $lastname Apellido(s)
 * @property int $document_type_id Tipo de documento de identidad
 * @property string $document Número de documento
 * @property string $phone Teléfono móvil
 * @property string $telephone Teléfono fijo
 * @property string $email Correo electrónico
 * @property date $birthdate Fecha de nacimiento
 * @property int $birthdate_place_id Ciudad de nacimiento
 * @property string $address Dirección
 * @property string $image_url URL base para la imagen
 * @property string $image Nombre del archivo de imagen
 * @property boolean $has_data_person Indicador si tiene datos personales completos
 * @property boolean $has_data_academic Indicador si tiene datos académicos
 * @property boolean $has_data_company Indicador si tiene datos laborales
 * 
 * Documentado por: Camilo Gomez
 */
class Person extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email',
        'birthdate', 'birthdate_place_id', 'address', 'image_url', 'image', 'has_data_person',
        'has_data_academic', 'has_data_company'
    ];

    /**
     * Get User's fullname.
     * 
     * @return string
     */
    public function fullname()
    {
        return ucwords($this->name . ' ' . $this->lastname);
    }

    /**
     * Get full asset image
     * 
     * @return string
     */
    public function fullAsset()
    {
        return $this->image_url . $this->image;
    }

    /** Relation Methods */
    public function personCompany()
    {
        return $this->hasMany(PersonCompany::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function personAcademic()
    {
        return $this->hasMany(PersonAcademic::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }


    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function birthdatePlace()
    {
        return $this->belongsTo(City::class, 'birthdate_place_id', 'id');
    }

     /**
     * 
     * @return bool
     */
    public function has_data_person()
    {
        return  $this->has_data_person == 1 ? true : false;
    }

    /**
     * 
     * @return bool
     */
    public function has_data_academic()
    {
        return  $this->has_data_academic == 1 ? true : false;
    }

    /**
     * 
     * @return bool
     */
    public function has_data_company()
    {
        return  $this->has_data_company == 1 ? true : false;
    }
}
