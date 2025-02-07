<?php

namespace App\Models;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Files extends Model
{
    use HasFactory, MediaAlly;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [

        'file_name',
        'file_size',
        'file_path',
        'file_type',
        'category',
        'sender_id',
        'receiver_id',
        'title',
        'description',
        'subject',
        'dept_in_request',
        'original_file_name',
        'assigned_to',
        'status'
    ];

    /**
     * user
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function archivedFiles()
    {
        return $this->belongsTo(ArchivedFiles::class, "file_id");
    }
}
