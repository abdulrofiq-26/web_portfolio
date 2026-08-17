<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'tech_stack',
        'project_url',
        'github_url',
    ];

    // Cast tech_stack dari JSON di DB menjadi Array PHP secara otomatis
    protected $casts = [
        'tech_stack' => 'array',
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}