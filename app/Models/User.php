<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'foto_path',
        'password',
        'rol_id',
        'empresa_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function adminlte_profile_url()
    {
        // Aquí debes devolver la URL del perfil del usuario
        // Puede ser algo como esto:
        return asset('vendor/adminlte/dist/img/profile-user.png');
    }


    public function adminlte_image()
    {
        // Aquí debes devolver la URL del perfil del usuario
        // Puede ser algo como esto:
        return asset('vendor/adminlte/dist/img/profile-user.png');
    }
   
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }
    public function empresa(): BelongsTo {
        return $this->belongsTo(Empresa::class, "empresa_id", "id");  
    }
}
