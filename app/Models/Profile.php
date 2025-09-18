<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Notifiable;
    use HasFactory;
    protected $fillable = [
        'telegram_user_id',
        'name',
        'email',
        'marital_status',
        'dob',
        'state',
        'city',
        'mother_tongue',
        'religion',
        'caste',
        'education_level',
        'education_field',
        'working_sector',
        'profession',
        'phone',
        'profile_photo',
        'diet',
        'smoking',
        'drinking',
        'height',
        'body_type',
        'skin_tone',
        'gender',
        'job_status',
        'bio',
        'income_range',
        'sub_caste',
        'specific_profession',
        'chovihar',
        'birth_time',
        'birth_place',
        'native_place',
        'terms_and_conditions'
    ];
    // app/Models/Profile.php

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'profile_id'); // Adjust if needed
    }

    public function routeNotificationForTelegram()
    {
        return $this->telegram_user_id; // Telegram chat ID
    }

    public function preference()
    {
        return $this->hasOne(\App\Models\Preference::class, 'profile_id', 'id');
    }
    // public function gallery()
    // {
    //     return $this->hasOne(Gallery::class)->latestOfMany();
    // }

    public function state()
    {
        return $this->belongsTo(State::class, 'state'); // 'state' is foreign key
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city'); // 'city' is foreign key
    }

    public function gallery()
{
    return $this->hasMany(Gallery::class);
}

}
