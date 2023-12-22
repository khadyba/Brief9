<?php

use Tests\TestCase;
use App\Models\Formation;
use App\Models\Formations;
use App\Models\User;

class CandidatsPostulerControllerTest extends TestCase
{
    public function testEnregistrementCandidature()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $formation = User::factory()->create();
        $response = $this->post("/api/formation/candidat/1");
        $response->assertStatus(200); 
    }
}
