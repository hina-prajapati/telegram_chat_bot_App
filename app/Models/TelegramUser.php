<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'telegram_user_id',
        'username',
        'first_name',
        'last_name',
        'language_code',
        'marital_status',
        'name',
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
        'profile_photo',
        'phone',
        'diet',
        'smoking',
        'drinking',
        'gender',
        'height',
        'body_type',
        'skin_tone',

        // Let us know how you see your future life partner
        'partner_marital_status',
        'partner_caste',
        'partner_min_age',
        'partner_max_age',
        'partner_min_height',
        'partner_max_height',
        'partner_gender',
        'partner_language'

    ];

    public function messages()
    {
        return $this->hasMany(TelegramMessage::class);
    }
    public function state()
    {
        return $this->hasOne(TelegramUserState::class);
    }public function gallery()
    {
        return $this->hasMany(Gallery::class, 'telegram_user_id');
    }
    public function galleries()
{
    return $this->hasMany(Gallery::class, 'telegram_user_id', 'telegram_user_id');
}
public function routeNotificationForTelegram()
{
    return $this->telegram_user_id; // Telegram chat ID
}

}
