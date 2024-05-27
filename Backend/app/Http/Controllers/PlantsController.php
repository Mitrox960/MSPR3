<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plante;
use Illuminate\Support\Facades\Storage;


class PlantsController extends Controller
{
   public function createPlant(Request $request)
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return response()->json(['message' => 'Aucun utilisateur connecté'], 401);
    }

    // Valider les données de la requête
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'required|string|max:255',
        'conseil_entretien' => 'required|string|max:255',
    ]);

    try {
        // Récupérer l'utilisateur actuellement authentifié
        $utilisateur = Auth::user();

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('plants', 'public'); // Stocker l'image dans le disque public/plants

            // Créer une nouvelle plante associée à cet utilisateur
            $plante = $utilisateur->plantes()->create([
                'nom' => $validatedData['nom'],
                'image' => $path, // Enregistrer le chemin de l'image
                'description' => $validatedData['description'],
                'conseil_entretien' => $validatedData['conseil_entretien'],
            ]);

            // Retourner les détails de la plante créée
            return response()->json($plante, 201); // 201 signifie Created
        } else {
            return response()->json(['error' => 'Aucune image téléchargée'], 400);
        }
    } catch (\Exception $e) {
        // En cas d'erreur, retourner une réponse JSON avec le message d'erreur approprié
        return response()->json(['error' => 'Une erreur s\'est produite lors de la création de la plante.', 'message' => $e->getMessage()], 500);
    }
}

   public function getUserPlants()
   {
       try {
           if (!Auth::check()) {
               return response()->json(['message' => 'Aucun utilisateur connecté'], 401);
           }

           $utilisateur = Auth::user();

           $plantes = $utilisateur->plantes;

           // Mettre à jour le chemin de l'image pour chaque plante
           foreach ($plantes as $plante) {
               // Construire l'URL publique pour l'image
               $plante->image_url = asset('storage/' . $plante->image);
           }

           return response()->json($plantes, 200);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération des plantes de l\'utilisateur.', 'message' => $e->getMessage()], 500);
       }
   }

    public function postPlant(Plante $plante)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Aucun utilisateur connecte'], 401);
        }

        try {

            $plante->update(['postee' => true]);

            return response()->json(['message' => 'Plante postee'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la mise à jour de la plante', 'message' => $e->getMessage()], 500);
        }
    }

    public function removePlant(Plante $plante)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Aucun utilisateur connecte'], 401);
        }

        try {

            $plante->update(['postee' => false]);

            return response()->json(['message' => 'Plante retiree'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la mise à jour de la plante', 'message' => $e->getMessage()], 500);
        }
    }

   public function deletePlant(Plante $plante)
    {
    try {
        if ($plante->id_utilisateur !== auth()->id()) {
            return response()->json([
                'error' => 'Vous n\'etes pas autorise a supprimer cette plante.'
            ], 403);
        }

        // Construire le chemin complet de l'image à supprimer
        $imagePath = public_path('storage/' . $plante->image);

        // Vérifier si le fichier existe avant de tenter de le supprimer
        if (file_exists($imagePath)) {
            // Supprimer l'image du dossier public
            unlink($imagePath);
        }

        // Supprimer la plante de la base de données
        $plante->delete();

        return response()->json(['message' => 'La plante a ete supprimee avec succes.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Une erreur s\'est produite lors de la suppression de la plante.', 'message' => $e->getMessage()], 500);
    }
}


   public function allPlants()
   {
       try {
           // Récupérer toutes les plantes avec les informations sur le propriétaire
           $plantes = Plante::with(['utilisateur.adresse'])->where('postee', true)->get();

           // Mettre à jour le chemin de l'image pour chaque plante
           foreach ($plantes as $plante) {
               // Construire l'URL publique pour l'image
               $plante->image_url = asset('storage/' . $plante->image);
           }

           return response()->json($plantes);
       } catch (\Exception $e) {
           // Journalisez l'erreur
           \Log::error('Erreur lors de la récupération des plantes : ' . $e->getMessage());
           // Retournez une réponse d'erreur avec le message complet de l'exception
           return response()->json(['message' => 'Une erreur est survenue lors de la récupération des plantes.', 'error' => $e->getMessage()], 500);
       }
   }
}
