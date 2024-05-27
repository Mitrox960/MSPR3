<?php

// Déclaration de l'espace de noms. Il correspond au chemin où se trouve ce fichier de test dans l'application.
namespace Tests\Feature;

// Importation des dépendances nécessaires pour le test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Importe le trait pour réinitialiser la base de données entre les tests.
use Tests\TestCase; // Classe de base pour tous les tests.
use App\Models\User; // Modèle User de l'application.
use App\Models\Plante; // Modèle Plante de l'application.

// Déclaration de la classe de test, qui hérite de TestCase.
class supprimerplanteTest extends TestCase
{
    use RefreshDatabase; // Utilisation du trait pour s'assurer que la base de données est propre avant chaque test.

    // Méthode de test pour la suppression d'une plante. Le nom de la méthode devrait commencer par "test" pour suivre les conventions (ex. : testSupprimerPlante).
    public function testSupprimerplante()
    {
        // Crée un utilisateur factice dans la base de données.
        $user = User::factory()->create();
        // Simule une session d'authentification pour cet utilisateur, précisant qu'il s'agit d'une authentification via l'API.
        $this->actingAs($user, 'api');
        // Crée une plante factice appartenant à l'utilisateur créé précédemment.
        $plante = Plante::factory()->create(['user_id' => $user->id]);

        // Envoie une requête HTTP DELETE à l'endpoint de suppression de la plante, en utilisant l'ID de la plante créée.
        $response = $this->json('DELETE', "/api/plants/delete/{$plante->id}");

        // Vérifie que la requête de suppression a retourné un statut HTTP 200, indiquant un succès.
        $response->assertStatus(200);
        // Vérifie également que la plante n'existe plus dans la base de données, confirmant qu'elle a été supprimée.
        $this->assertDatabaseMissing('plantes', ['id' => $plante->id]);
    }
}
