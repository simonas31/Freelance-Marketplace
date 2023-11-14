<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'name', 'surname', 'password', 'role', 'confirmed_registration', 'rating'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    protected $usersRoles = ['client' => 1, 'freelancer' => 2, 'admin' => 200];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    public function canEnterNextRoute($guards): bool
    {
        $enter = [];
        foreach ($guards as $key => $guard){
            if ($this->role == $this->usersRoles[$guard]){
                $enter[] = true;
            }else{
                $enter[] = false;
            }
        }
        return in_array(true, $enter);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function portfolio()
    {
        return $this->hasOne(Portfolio::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'freelancer_id');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return ['role' => $this->role];
    }
}
