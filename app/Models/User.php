<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification; // (Nanti kita buat jika diperlukan, atau langsung override method bawaan)

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan kolom baru kita ke dalam fillable
    protected $fillable = [
        'name',
        'headline',
        'headlines',
        'phone',
        'photo_position',
        'email',
        'password',
        'username',
        'bio',
        'home_bio',
        'hero_experience_years',
        'hero_icon',
        'profile_photo',
        'primary_color',
        'secondary_color',
        'location',
        'availability',
        'cv_path',
        'section_visibility',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'headlines' => 'array',
        'section_visibility' => 'array',
    ];

    // Relasi ke Experience (Satu user punya banyak pengalaman)
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    // Relasi ke Project (Satu user punya banyak proyek)
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Relasi ke Skill (Satu user punya banyak keahlian)
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    // Relasi ke Social Media
    public function socialMedias()
    {
        return $this->hasMany(SocialMedia::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function sendPasswordResetNotification($token)
    {
        // Cukup kirim tokennya, Laravel otomatis akan merakit URL-nya menggunakan route 'password.reset'
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}