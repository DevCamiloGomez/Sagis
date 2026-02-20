<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'company_name',
        'location',
        'salary',
        'type',
        'contact_email',
        'admin_id',
        'is_active',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}