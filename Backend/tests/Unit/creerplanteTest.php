<?php

// Déclaration de l'espace de noms, permettant d'organiser le code et de faciliter son autochargement.
namespace Tests\Feature;

// Importation des classes nécessaires pour le test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Ce trait est utilisé pour réinitialiser la base de données entre les tests, assurant ainsi qu'aucun test ne sera affecté par les données laissées par les tests précédents.
use Illuminate\Http\UploadedFile; // Facilite la création de fichiers factices pour les tests.
use Illuminate\Support\Facades\Storage; // Facade pour interagir avec le système de fichiers.
use Tests\TestCase; // Classe de base pour tous les tests.
use App\Models\User; // Importe le modèle User pour créer des utilisateurs factices dans la base de données.

// Définition de la classe de test, qui étend la classe TestCase.
class creerplanteTest extends TestCase
{
    use RefreshDatabase; // Utilisation du trait RefreshDatabase pour chaque test, assurant une base de données propre.

    // Méthode de test pour créer une plante. Le nom devrait commencer par 'test' pour que PHPUnit la reconnaisse automatiquement comme une méthode de test.
    public function testCreerplante()
    {
        Storage::fake('public'); // Simule un disque de stockage 'public', permettant de tester l'upload de fichiers sans affecter le système de fichiers réel.

        $user = User::factory()->create(); // Crée un utilisateur factice et le stocke dans la base de données.
        $this->actingAs($user, 'api'); // Simule l'authentification de cet utilisateur via l'API.

        // Envoie une requête POST à l'endpoint de création de plante avec des données de test, y compris une image factice.
        $response = $this->json('POST', '/api/plants/create', [
            'nom' => 'Plante Test', // Nom de la plante
            'image' => UploadedFile::fake()->image('plante.jpg'), // Crée une image factice pour l'upload
            'description' => 'Description test', // Description de la plante
            'conseil_entretien' => 'Conseils test', // Conseils d'entretien pour la plante
        ]);

        // Assure que la requête a réussi avec un statut HTTP 201, indiquant la création réussie de la ressource.
        $response->assertStatus(201); 
    }
}
