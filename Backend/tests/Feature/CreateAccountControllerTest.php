<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use App\Http\Controllers\CreateAccountController;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;
use App\Models\Adresse;
use App\Models\Role;

class CreateAccountControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_register_new_user()
    {
        // Arrange
        $request = new Request([
            'nom' => 'John',
            'prenom' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'ville' => 'City',
            'code_postal' => '12345',
            'nom_voie' => 'Street',
            'numero_voie' => '123',
            'role' => 'utilisateur',
            'date_de_naissance' => '1990-01-01',
            'telephone' => '123456789',
        ]);

        $controller = new CreateAccountController();

        // Act
        $response = $controller->register($request);

        // Assert
        $this->assertTrue($response->isRedirect());

        $this->assertDatabaseHas('utilisateurs', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'adresse_mail' => 'john.doe@example.com',
        ]);

        $user = Utilisateur::where('adresse_mail', 'john.doe@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->mot_de_passe));
    }
}
