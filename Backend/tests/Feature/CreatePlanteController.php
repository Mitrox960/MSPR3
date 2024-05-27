<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
//use App\Http\Controllers\PlanteController;
use App\Models\Utilisateur;

class CreatePlanteControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_creation_plante()
    {
        //Arrange
        $utilisateur = Utilisateur::factory()->create();

        $image = UploadedFile::fake()->image('test.jpg');

        //Act
       // $controller = new PlanteController();

        $response = $controller->creationPlante([
            'nom' => 'Nom de la plante',
            'image' => $image,
            'description' => 'Description de la plante',
            'conseil_entretien' => 'Conseil d\'entretien de la plante',
        ]);

        //Assert

        $this->assertTrue($response->isRedirect());

        $this->assertDatabaseHas('plantes', [
            'nom' => 'Nom de la plante',
            'description' => 'Description de la plante',
            'conseil_entretien' => 'Conseil d\'entretien de la plante',
        ]);

        $this->assertDatabaseHas('plantes', [
            'utilisateur_id' => $utilisateur->id,
        ]);

        Storage::disk('public')->assertExists($response->image);
    }
}
