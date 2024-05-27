<?php

namespace App\Services;

use App\Models\Plante;

class PlanteService
{
    protected Plante $plante;

    public function __construct(Plante $plante)
    {
        $this->plante = $plante;
    }

    public function getAll()
    {
        return $this->plante->all();
    }

    public function getById($id)
    {
        return $this->plante->find($id);
    }

    public function createPlante($nom, $image, $desc, $conseil_entret, $id_session_de_garde)
    {
        return $this->plante->create([
            'nom' => $nom,
            'image' => $image,
            'description' => $desc,
            'conseil_entretien' => $conseil_entret,
            'id_session_de_garde' => $id_session_de_garde,
        ]);
    }
}
