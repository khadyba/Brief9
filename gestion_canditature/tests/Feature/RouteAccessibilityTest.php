<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteAccessibilityTest extends TestCase
{
    /**
     * Teste l'accessibilité de la route /dashbordAdmin.
     *
     * @return void
     */
    public function testDashboardAdminRouteIsAccessible()
    {
        $response = $this->get('/api/formations');
        $response->assertStatus(200); 
       
    }

    public function testDashboardAdminRouteAccessible()
    {
        $admin = User::factory()->create(); 
        $this->actingAs($admin);
        $response = $this->get('/api/dashbordAdmin');
        $response->assertStatus(200);
    }
    
    public function testListeCandidatsInscrits()
    {
        $admin = User::factory()->create(); 
        $this->actingAs($admin);
        $response = $this->get('/api/candidats');
        $response->assertStatus(200);
    }

    public function testListeCandidatures()
    {
        $admin = User::factory()->create(); 
        $this->actingAs($admin);
        $response = $this->get('/api/formations/candidatureList');
        // dd($response);
        $response->assertStatus(200);

    }

    public function testLoginCandidat()
    {
        $password = 'mot_de_passe'; 
        $candidat = User::factory()->create([
            'roles' => 'user',
            'password' => Hash::make($password),
        ]);
        $response = $this->post('/api/auth/login', [
            'email' => $candidat->email,
            'password' => $password, 
        ]);
            $response->assertStatus(200); 
        }
        public function testLoginAdmin()
        {
            $password = 'mot_de_passe'; 
            $candidat = User::factory()->create([
                'roles' => 'admin',
                'password' => Hash::make($password),
            ]);
            $response = $this->post('/api/auth/login', [
                'email' => $candidat->email,
                'password' => $password, 
            ]);
                $response->assertStatus(200); 
            }
            public function testFormatListeCandidatures()
            {
                $admin = User::factory()->create(); 
                $this->actingAs($admin);
                $response = $this->get('/api/formations/candidatureList');
                $response->assertStatus(200); 
                $response->assertJsonStructure([
                    'status_code',
                    'candidatures' => [
                        '*' => [
                            'id',
                            'statut',
                            'user_id',
                            'formations_id',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]);
            }

            
       

}
