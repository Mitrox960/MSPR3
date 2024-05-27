<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plante;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'prenom',
        'date_de_naissance',
        'adresse_mail',
        'mot_de_passe',
        'telephone',
        'id_adresse',
        'id_role',
    ];

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'id_adresse');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function sessionsDeGarde()
    {
        return $this->hasMany(SessionDeGarde::class, 'id_utilisateur');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'id_utilisateur');
    }

     public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

     public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function plantes()
    {
        return $this->hasMany(Plante::class, 'id_utilisateur');
    }

    // Ajoute d'autres relations si n�cessaire

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
