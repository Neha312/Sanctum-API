<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Role as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'role_name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
