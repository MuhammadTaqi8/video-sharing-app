<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'Roles';

    protected $fillable = [
        'RoleName',
    ];

    // Define a one-to-many relationship with the User model
    public function users()
    {
        return $this->hasMany(User::class, 'RoleID');
    }
}
