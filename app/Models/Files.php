<?php

namespace App\Models;

use App\Models\User;
use App\Models\ArchivedFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Files extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [

        'original_file_name',
        'file_name',
        'file_size',
        'file_url',
        'file_type',
        'title',
        'description',
        'sender_id',
        'receiver_id',
        'subject',
        'dept_in_request',
        'assigned_to',
        'assigned_from',
        'comment',
        'archived',
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
