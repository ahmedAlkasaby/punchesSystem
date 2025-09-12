<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\ActivityLogTrait;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable  implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles,HasApiTokens,ActivityLogTrait,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->type === 'admin';
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'active',
        'lang',
        'theme',
        'last_punched_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

      public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [
        'email'=>$this->email,
        'name'=>$this->name
      ];
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id', 'id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'user_location')
            ->withPivot('primary_location')
            ->withTimestamps();
    }

    public function activeLocations()
    {
        return $this->belongsToMany(Location::class, 'user_location')
            ->withPivot('primary_location')
            ->withTimestamps()
            ->active();
    }

    public function punches()
    {
        return $this->hasMany(Punch::class, 'user_id', 'id');
    }

  


}
