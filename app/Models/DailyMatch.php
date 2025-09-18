<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMatch extends Model
{
    use HasFactory;
    protected $fillable = ['telegram_user_id', 'match_date', 'shown_at', 'matched_user_id'];
    public function matchedProfile()
{
    return $this->belongsTo(Profile::class, 'matched_user_id');
}
}
