<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserState extends Model
{
    use HasFactory;

    protected $fillable = ['telegram_user_id', 'current_step', 'answers', 'language'];

    protected $casts = [
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
