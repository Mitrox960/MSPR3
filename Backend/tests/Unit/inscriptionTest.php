<?php

// Espace de noms qui suit la structure des dossiers de l'application Laravel, nécessaire pour l'auto-chargement.
namespace Tests\Feature;

// Importation des dépendances nécessaires au test.
use Illuminate\Foundation\Testing\RefreshDatabase; // Ce trait est utilisé pour rafraîchir la base de données entre les tests, garantissant ainsi qu'aucun test n'est affecté par les modifications apportées par les autres.
use Tests\TestCase; // Classe de base pour tous les tests dans Laravel.
use App\Models\User; // Importe le modèle User pour pouvoir interagir avec la table des utilisateurs dans la base de données.
use App\Models\Adresse; // Importe le modèle Adresse pour tester les interactions avec la table des adresses.
use App\Models\Role; // Importe le modèle Role pour tester les interactions avec la table des rôles.

// Déclaration de la classe de test qui étend la classe TestCase.
class inscriptionTest extends TestCase
{
    use RefreshDatabase; // Application du trait pour s'assurer que la base de données est réinitialisée avant chaque test.

    // Méthode de test pour l'inscription. Le nom de la méthode devrait idéalement commencer par "test" pour suivre les conventions de PHPUnit.
    public function testInscription()
    {
        // Préparation des données de l'utilisateur à inscrire.
        $userData = [
            'nom' => 'Dagba',
            'prenom' => 'Didier',
            'adresse_mail' => 'didierdrogbagoat@gmail.com',
            'mot_de_passe' => 'password', // Doit être hashé lors de l'inscription dans l'application réelle.
            'mot_de_passe_confirmation' => 'password', // Pour la validation de confirmation du mot de passe.
            'ville' => 'sevran',
            'code_postal' => '93800',
            'nom_voie' => 'Rue de Michel',
            'numero_voie' => '123',
            'nom_role' => 'utilisateur', // Supposant que l'application attribue des rôles aux utilisateurs.
            'date_de_naissance' => '1990-01-01',
            'telephone' => '0102030405',
        ];

        // Envoi d'une requête POST à l'endpoint '/register' avec les données de l'utilisateur.
        $response = $this->json('POST', '/register', $userData);

        // Vérification que la réponse a un statut HTTP 201, indiquant la création réussie.
        $response->assertStatus(201);
        
        // Assertions pour vérifier que les données de l'utilisateur sont correctement enregistrées dans la base de données.
        // Note: Il y a une incohérence ici, car l'adresse mail utilisée pour l'assertion 'users' devrait correspondre à celle fournie dans $userData.
        $this->assertDatabaseHas('users', [
            'adresse_mail' => 'didierdrogbagoat@gmail.com',
        ]);
        // Note: La ville devrait correspondre à celle fournie dans $userData pour la cohérence du test.
        $this->assertDatabaseHas('adresses', [
            'ville' => 'sevran',
        ]);
        // Vérification de l'enregistrement du rôle.
        $this->assertDatabaseHas('roles', [
            'nom_role' => 'utilisateur',
        ]);
    }
}

