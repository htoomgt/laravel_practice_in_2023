<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_content',
        'content_body',
        'image_file',
        'status',
        'author_id'
    ];


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
