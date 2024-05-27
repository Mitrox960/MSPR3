<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adresse;
use App\Models\Role;

class CreateUserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'adresse_mail' => 'required|email|unique:utilisateurs,adresse_mail',
                'mot_de_passe' => 'required|string|min:8|confirmed',
                'ville' => 'required|string|max:255',
                'code_postal' => 'required|string|max:10',
                'nom_voie' => 'required|string|max:255',
                'numero_voie' => 'required|string|max:20',
                'nom_role' => 'required|in:botaniste,utilisateur',
                'date_de_naissance' => 'required|date',
                'telephone' => 'required|string|max:20',
            ]);

            $role = Role::firstOrCreate(
                ['nom_role' => $request->nom_role], // Recherche par nom_role
            );

            $adresse = Adresse::create([
                'ville' => $request['ville'],
                'code_postal' => $request['code_postal'],
                'nom_voie' => $request['nom_voie'],
                'numero_voie' => $request['numero_voie'],
            ]);

            // Créez un nouvel utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'adresse_mail' => $request->adresse_mail,
                'mot_de_passe' => bcrypt($request->mot_de_passe),
                'date_de_naissance' => $request->date_de_naissance,
                'telephone' => $request->telephone,
                'id_role' => $role->id,
                'id_adresse' => $adresse->id,
            ]);

            // Retournez les informations du nouvel utilisateur en JSON
            return response()->json($user, 201);
        } catch (\Exception $e) {
            // Journalisez l'erreur
            \Log::error('Error creating user: ' . $e->getMessage());
            // Retournez une réponse d'erreur avec le message complet de l'exception
            return response()->json(['message' => 'An error occurred while creating the user.', 'error' => $e->getMessage()], 500);
        }
    }
}
