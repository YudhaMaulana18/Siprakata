<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';
    protected $fillable = ['user_id', 'token', 'name'];
    protected $hidden = ['token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
