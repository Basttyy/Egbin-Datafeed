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
    protected $table = "role";
    protected $fillable = [
        'user_id',
        'role'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
