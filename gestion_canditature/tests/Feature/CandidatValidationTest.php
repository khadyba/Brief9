<?php

use Tests\TestCase;
use App\Models\Candidat;
use App\Models\User;

class CandidatValidationTest extends TestCase
{
    public function testEmailUnique()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $data = [
            'email' => 'test@example.com', 
        ];

        $candidat = User::factory()->make($data);
        $this->assertFalse($candidat->save()); 
        $this->assertNotNull($candidat->getError('email')); 
    }
    public function testPrenomString()
    {
        $data = [
            'prenom' => 123, 
        ];

        $candidat = User::factory()->make($data);

        $this->assertFalse($candidat->save()); 
        $this->assertNotNull($candidat->getError('prenom')); 
    }

               public function testRegister()
               {
                  User::factory()->create();
                 $response = $this->post('/api/auth/register');
                 $response->assertStatus(200); 
                 
               }

}

