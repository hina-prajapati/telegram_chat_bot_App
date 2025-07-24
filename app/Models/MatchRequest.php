<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchRequest extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    public function senderProfile()
    {
        return $this->belongsTo(Profile::class, 'sender_id');
    }

}
