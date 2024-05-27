<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plante;

class PlantsController extends Controller
{
    public function createPlant(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json(['message' => 'Aucun utilisateur connecte']);


        }    // Gérez l'upload de l'image
        /*  $image = base64_encode(file_get_contents($request->file('image')->path()));
            $plante = $utilisateur->plantes()->create([
                'nom' => $validatedData['nom'],
                'image' => $image,
                'description' => $validatedData['description'],
                'conseil_entretien' => $validatedData['conseil_entretien'],
            ]);*/

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            //'image' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|max:255',
            'conseil_entretien' => 'required|string|max:255',
        ]);

        try {
            // Récupérez l'utilisateur actuellement authentifié
            $utilisateur = Auth::user();

            $image = base64_encode(file_get_contents($request->file('image')->path()));
            // Créer une nouvelle plante associée à cet utilisateur
            $plante = $utilisateur->plantes()->create([
                'nom' => $validatedData['nom'],
                //'image' => $validatedData['image'],
                'image' => $image,
                'description' => $validatedData['description'],
                'conseil_entretien' => $validatedData['conseil_entretien'],
            ]);

            // Retourner les détails de la plante créée
            return response()->json($plante, 201); // 201 signifie Created
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le message d'erreur approprié
            return response()->json(['error' => 'Une erreur s\'est produite lors de la création de la plante.', 'message' => $e->getMessage()], 500);
        }
    }
    public function getUserPlants()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Aucun utilisateur connecte']);
            }

            $utilisateur = Auth::user();

            $plantes = $utilisateur->plantes;

            // Décoder les images encodées en base64 avant de les renvoyer
            foreach ($plantes as $plante) {
                // Vérifier si l'image est encodée en base64
                if (strpos($plante->image, 'data:image/jpeg;base64,') === 0) {
                    // Supprimer le préfixe "data:image/jpeg;base64,"
                    $imageData = substr($plante->image, strpos($plante->image, ',') + 1);
                    // Décoder l'image base64 en format binaire
                    $decodedImage = base64_decode($imageData);
                    // Enregistrer l'image décodée dans un fichier temporaire
                    $tempImagePath = tempnam(sys_get_temp_dir(), 'plant_image');
                    file_put_contents($tempImagePath, $decodedImage);
                    // Convertir l'image en JPEG
                    $jpegImagePath = tempnam(sys_get_temp_dir(), 'plant_image') . '.jpg';
                    $image = imagecreatefromjpeg($tempImagePath);
                    imagejpeg($image, $jpegImagePath, 100);
                    imagedestroy($image);
                    // Lire le contenu de l'image JPEG
                    $jpegImageData = file_get_contents($jpegImagePath);
                    // Supprimer les fichiers temporaires
                    unlink($tempImagePath);
                    unlink($jpegImagePath);
                    // Mettre à jour l'image de la plante avec l'image JPEG
                    $plante->image = $jpegImageData;
                }
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
            // Décoder les images encodées en base64 avant de les renvoyer
            foreach ($plantes as $plante) {
                // Vérifier si l'image est encodée en base64
                if (strpos($plante->image, 'data:image/jpeg;base64,') === 0) {
                    // Supprimer le préfixe "data:image/jpeg;base64,"
                    $imageData = substr($plante->image, strpos($plante->image, ',') + 1);
                    // Décoder l'image base64 en format binaire
                    $decodedImage = base64_decode($imageData);
                    // Enregistrer l'image décodée dans un fichier temporaire
                    $tempImagePath = tempnam(sys_get_temp_dir(), 'plant_image');
                    file_put_contents($tempImagePath, $decodedImage);
                    // Convertir l'image en JPEG
                    $jpegImagePath = tempnam(sys_get_temp_dir(), 'plant_image') . '.jpg';
                    $image = imagecreatefromjpeg($tempImagePath);
                    imagejpeg($image, $jpegImagePath, 100);
                    imagedestroy($image);
                    // Lire le contenu de l'image JPEG
                    $jpegImageData = file_get_contents($jpegImagePath);
                    // Supprimer les fichiers temporaires
                    unlink($tempImagePath);
                    unlink($jpegImagePath);
                    // Mettre à jour l'image de la plante avec l'image JPEG
                    $plante->image = $jpegImageData;
                }
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
