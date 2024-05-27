<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plante extends Model
{
    protected $table = 'plantes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'image',
        'description',
        'conseil_entretien',
        'id_session_de_garde',
        'id_utilisateur',
        'postee',
    ];

    protected $attributes = [
        'postee' => false, // Remplacez "votre_nouveau_champ" par le nom réel de votre champ booléen
    ];

    public function sessionDeGarde()
    {
        return $this->belongsTo(SessionDeGarde::class, 'id_session_de_garde');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function createPlante(string $nom, string $image, string $desc, string $conseil_entret, $id_session_de_garde, $id_utilisateur)
    {
        $plante = Plante::create([
            'nom' => $nom,
            'image' => $image,
            'description' => $desc,
            'conseil_entretien' => $conseil_entret,
            'id_session_de_garde' => $id_session_de_garde,
            'id_utilisateur' => $id_utilisateur,
            // Ne pas inclure le champ booléen ici car il sera défini par défaut
        ]);
    }

    // Ajoutez d'autres relations ou méthodes si nécessaire
}
