<?php

// Namespace définissant l'emplacement du fichier de test dans l'architecture de l'application.
namespace Tests\Feature;

// Importations nécessaires pour le test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Permet de rafraîchir la base de données entre chaque test, garantissant un environnement de test propre.
use Tests\TestCase; // Classe de base pour tous les tests.
use App\Models\User; // Le modèle User pour créer des utilisateurs factices dans les tests.

// Définition de la classe de test, qui hérite de TestCase.
class infouserTest extends TestCase
{
    use RefreshDatabase; // Utilisation du trait RefreshDatabase pour s'assurer que chaque test démarre avec une base de données vierge.

    // Définition de la méthode de test. Cette méthode devrait être préfixée par "test" pour être reconnue automatiquement par PHPUnit, par exemple, "testInfoUser".
    public function testInfouser()
    {
        // Crée un nouvel utilisateur factice et l'insère dans la base de données.
        $user = User::factory()->create();

        // Simule une action d'utilisateur authentifié en envoyant une requête GET à l'endpoint '/api/me'.
        $response = $this->actingAs($user, 'api')->json('GET', '/api/me');

        // Vérifie que la réponse HTTP reçue est 200 OK, indiquant que la requête a été traitée avec succès.
        $response->assertStatus(200);
        // Vérifie que la réponse contient un JSON avec les informations spécifiques de l'utilisateur, ici son id et son adresse mail.
        $response->assertJson([
            'id' => $user->id,
            'adresse_mail' => $user->adresse_mail,
        ]);
    }
}
