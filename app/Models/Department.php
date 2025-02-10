<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'department',
        'type'
    ];

    public function staff()
    {
        return $this->hasMany(User::class, 'department');
    }
}