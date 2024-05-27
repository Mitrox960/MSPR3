<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionDeGarde extends Model
{
    protected $table = 'sessions_de_garde';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'date_de_debut',
        'date_de_fin',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'id_session_de_garde');
    }

    public function plantes()
    {
        return $this->hasMany(Plante::class, 'id_session_de_garde');
    }

    public function seanceEntretiens()
    {
        return $this->hasMany(SeanceEntretien::class, 'id_session_de_garde');
    }

    // Ajoute d'autres relations si n�cessaire
}
