<?php

namespace App\Models;

use App\Models\User;
use App\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArchivedFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'archived_by',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
    ];

    public function file()
    {
        return $this->belongsTo(Files::class);
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class);
    }
}