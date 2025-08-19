<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'partner_marital_status',
        'partner_caste',
        'partner_min_age',
        'partner_max_age',
        'partner_min_height',
        'partner_max_height',
        'partner_gender',
        'partner_language',
        'partner_income_range',
        'partner_religion',
        'partner_job_status',
        'profile_id',
        'partner_sub_caste',
        'partner_education_level',
        'partner_profession',
        'partner_specific_profession',
        'chovihar',
        'partner_diet',
        'partner_chovihar'
    ];

    protected $hidden = ['profile_id'];


    public function profile()
    {
        return $this->belongsTo(\App\Models\Profile::class, 'profile_id', 'id');
    }

}
