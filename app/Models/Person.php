<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
