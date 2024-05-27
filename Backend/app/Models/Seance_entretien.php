<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeanceEntretien extends Model
{
    protected $table = 'seance_entretiens';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'image',
        'date_entretien',
        'description',
        'etat_plante',
        'commentaire_botaniste',
        'id_session_de_garde',
    ];

    public function sessionDeGarde()
    {
        return $this->belongsTo(SessionDeGarde::class, 'id_session_de_garde');
    }

    // Ajoute d'autres relations si n�cessaire
}
