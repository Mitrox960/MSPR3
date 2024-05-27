<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $table = 'historiques';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'identifiant',
        'etat_de_garde',
        'date_garde',
        'note_garde',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Ajoute d'autres relations si n�cessaire
}
