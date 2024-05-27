<?php

// Déclaration de l'espace de noms. Il s'aligne sur la structure de dossiers pour les tests de fonctionnalités.
namespace Tests\Feature;

// Import des classes nécessaires au test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Ce trait réinitialise la base de données à son état initial après chaque test, pour éviter la contamination des données entre les tests.
use Tests\TestCase; // La classe de base pour tous les tests dans Laravel.
use App\Models\User; // Le modèle User, utilisé pour créer un utilisateur factice pour le test.

// Déclaration de la classe de test. Elle hérite de TestCase, qui fournit de nombreuses méthodes utiles pour effectuer des tests.
class deconnexionTest extends TestCase
{
    use RefreshDatabase; // Utilise le trait RefreshDatabase pour s'assurer que la base de données est réinitialisée avant chaque test.

    // Méthode du test de déconnexion. Le nom devrait idéalement commencer par "test", comme "testDeconnexion" pour suivre les conventions de nommage de PHPUnit.
    public function testDeconnexion()
    {
        // Crée un utilisateur factice dans la base de données en utilisant la factory de User.
        $user = User::factory()->create();

        // Simule une action où cet utilisateur est connecté via l'API et envoie une requête POST à l'endpoint '/api/logout' pour se déconnecter.
        $response = $this->actingAs($user, 'api')->json('POST', '/api/logout');

        // Vérifie que la réponse à la requête de déconnexion renvoie un statut HTTP 200, indiquant que la requête a été traitée avec succès.
        $response->assertStatus(200);
        // Vérifie également que la réponse contient un message JSON spécifique, confirmant la déconnexion réussie.
        $response->assertJson(['message' => 'Successfully logged out']);
    }
}
