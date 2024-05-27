<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use DateTime;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_send_login_failed_authentication()
    {
        // Arrange
        $request = new Request([
            'identifiant' => 'test@example.com',
            'mot_de_passe' => 'mot_de_passe_incorrect',
        ]);

        // Act
        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(false);

        Auth::shouldReceive('check')->andReturn(false);

        $response = (new LoginController())->sendLogin($request);

        // Assert
        $this->assertTrue($response->isRedirect());

        $this->assertFalse(Auth::check());

        $this->assertEquals(url()->previous(), $response->getTargetUrl());
    }
}
