<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationToken extends Model
{
    use HasFactory;

    protected $table = 'AuthenticationTokens';

    protected $fillable = [
        'UserID', 'Token', 'Expiry',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
