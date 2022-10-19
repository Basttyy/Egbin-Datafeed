<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const SUPER_ADMIN = 'super-admin';
    const ADMIN = 'admin';
    const USER = 'user';

    use HasFactory;
    protected $table = "roles";
    protected $fillable = [
        'role'
    ];

    public function user(){
        return $this->hasMany(User::class);
    }
}
