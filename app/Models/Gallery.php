<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'profile_id',
        'image_path',
    ];
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
