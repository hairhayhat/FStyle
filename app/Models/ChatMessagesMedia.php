<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessagesMedia extends Model
{
    use HasFactory;

    protected $table = 'message_media';

    protected $fillable = ['message_id', 'path', 'type'];

    public function messages()
    {
        return $this->belongsTo(ChatMessages::class, 'message_id');
    }
}
