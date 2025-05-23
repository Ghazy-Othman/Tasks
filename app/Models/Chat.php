<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $primaryKey = 'chat_id' ; 

    protected $fillable = [
        "user_id",
        "title"
    ];
    
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
}
