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
    // V�rifier si l'utilisateur est connect�
    if (!Auth::check()) {
        return response()->json(['message' => 'Aucun utilisateur connect�'], 401);
    }

    // Valider les donn�es de la requ�te
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'required|string|max:255',
        'conseil_entretien' => 'required|string|max:255',
    ]);

    try {
        // R�cup�rer l'utilisateur actuellement authentifi�
        $utilisateur = Auth::user();

        // G�rer l'upload de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('plants', 'public'); // Stocker l'image dans le disque public/plants

            // Cr�er une nouvelle plante associ�e � cet utilisateur
            $plante = $utilisateur->plantes()->create([
                'nom' => $validatedData['nom'],
                'image' => $path, // Enregistrer le chemin de l'image
                'description' => $validatedData['description'],
                'conseil_entretien' => $validatedData['conseil_entretien'],
            ]);

            // Retourner les d�tails de la plante cr��e
            return response()->json($plante, 201); // 201 signifie Created
        } else {
            return response()->json(['error' => 'Aucune image t�l�charg�e'], 400);
        }
    } catch (\Exception $e) {
        // En cas d'erreur, retourner une r�ponse JSON avec le message d'erreur appropri�
        return response()->json(['error' => 'Une erreur s\'est produite lors de la cr�ation de la plante.', 'message' => $e->getMessage()], 500);
    }
}

   public function getUserPlants()
   {
       try {
           if (!Auth::check()) {
               return response()->json(['message' => 'Aucun utilisateur connect�'], 401);
           }

           $utilisateur = Auth::user();

           $plantes = $utilisateur->plantes;

           // Mettre � jour le chemin de l'image pour chaque plante
           foreach ($plantes as $plante) {
               // Construire l'URL publique pour l'image
               $plante->image_url = asset('storage/' . $plante->image);
           }

           return response()->json($plantes, 200);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Une erreur s\'est produite lors de la r�cup�ration des plantes de l\'utilisateur.', 'message' => $e->getMessage()], 500);
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
            return response()->json(['error' => 'Une erreur s\'est produite lors de la mise � jour de la plante', 'message' => $e->getMessage()], 500);
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
            return response()->json(['error' => 'Une erreur s\'est produite lors de la mise � jour de la plante', 'message' => $e->getMessage()], 500);
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

        // Construire le chemin complet de l'image � supprimer
        $imagePath = public_path('storage/' . $plante->image);

        // V�rifier si le fichier existe avant de tenter de le supprimer
        if (file_exists($imagePath)) {
            // Supprimer l'image du dossier public
            unlink($imagePath);
        }

        // Supprimer la plante de la base de donn�es
        $plante->delete();

        return response()->json(['message' => 'La plante a ete supprimee avec succes.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Une erreur s\'est produite lors de la suppression de la plante.', 'message' => $e->getMessage()], 500);
    }
}


   public function allPlants()
   {
       try {
           // R�cup�rer toutes les plantes avec les informations sur le propri�taire
           $plantes = Plante::with(['utilisateur.adresse'])->where('postee', true)->get();

           // Mettre � jour le chemin de l'image pour chaque plante
           foreach ($plantes as $plante) {
               // Construire l'URL publique pour l'image
               $plante->image_url = asset('storage/' . $plante->image);
           }

           return response()->json($plantes);
       } catch (\Exception $e) {
           // Journalisez l'erreur
           \Log::error('Erreur lors de la r�cup�ration des plantes : ' . $e->getMessage());
           // Retournez une r�ponse d'erreur avec le message complet de l'exception
           return response()->json(['message' => 'Une erreur est survenue lors de la r�cup�ration des plantes.', 'error' => $e->getMessage()], 500);
       }
   }
}
