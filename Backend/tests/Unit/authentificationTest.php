<?php

// Définit l'espace de noms du fichier, qui correspond à son chemin dans l'arborescence de l'application.
namespace Tests\Feature;

// Importe les classes nécessaires pour ce fichier de test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Pour réinitialiser la base de données entre les tests.
use Tests\TestCase; // La classe de base pour les tests dans Laravel.
use App\Models\User; // Le modèle User pour créer des utilisateurs factices.

// Définit une classe de test, qui étend la classe de base TestCase.
class authentificationTest extends TestCase
{
    use RefreshDatabase; // Utilise le trait RefreshDatabase pour s'assurer que la base de données est réinitialisée avant chaque test.

    // Définit une méthode de test. Attention : dans Laravel, chaque méthode de test doit commencer par "test". Ici, le nom est mal choisi et devrait être "testAuthentification" par exemple.
    public function testAuthentification()
    {
        // Crée un nouvel utilisateur factice en utilisant la factory User. Cet utilisateur est sauvegardé dans la base de données.
        $user = User::factory()->create([
            'adresse_mail' => 'user@example.com', // Définit l'adresse mail de l'utilisateur.
            'password' => bcrypt('password'), // Hash le mot de passe avant de le sauvegarder.
        ]);

        // Envoie une requête POST à l'endpoint '/api/login' avec les identifiants de l'utilisateur.
        $response = $this->json('POST', '/api/login', [
            'adresse_mail' => 'user@example.com',
            'password' => 'password',
        ]);

        // Vérifie que la réponse à la requête a un statut HTTP 200, ce qui indique une réussite.
        $response->assertStatus(200);
        // Vérifie également que la réponse contient une structure JSON spécifique, incluant un token d'accès, le type de token, et le temps d'expiration.
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }
}
